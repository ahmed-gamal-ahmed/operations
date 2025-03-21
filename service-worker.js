// تحديد النسخة لسهولة تحديث التخزين المؤقت
const CACHE_NAME = 'shipment-tracker-cache-v2';

// قائمة الملفات التي سيتم تخزينها مؤقتًا
const urlsToCache = [
  './',
  './index.html',
  './manifest.json',
  './icons/icon-192.png',
  './icons/icon-512.png',
  'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js'
];

// تثبيت Service Worker
self.addEventListener('install', (event) => {
  // تفعيل التحديث الفوري للخدمة العاملة
  self.skipWaiting();
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('تم فتح التخزين المؤقت');
        return cache.addAll(urlsToCache);
      })
      .catch(err => console.error('خطأ في تخزين الملفات:', err))
  );
});

// تنشيط Service Worker
self.addEventListener('activate', (event) => {
  // تفعيل الخدمة على جميع نطاقات العميل
  self.clients.claim();
  
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            // حذف التخزينات المؤقتة القديمة
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// اعتراض طلبات الشبكة - استراتيجية "Cache First" ثم الشبكة
self.addEventListener('fetch', (event) => {
  // فحص إذا كان الطلب يستخدم بروتوكول غير مدعوم
  const url = new URL(event.request.url);
  
  // تجاهل الطلبات غير HTTP/HTTPS أو طلبات امتدادات كروم
  if (url.protocol !== 'http:' && url.protocol !== 'https:') {
    return; // عدم التعامل مع الطلب
  }
  
  // تجنب تخزين روابط الامتدادات
  if (event.request.url.includes('chrome-extension')) {
    return; // عدم التعامل مع الطلب
  }
  
  // استراتيجية Cache First للموارد الثابتة، Network First للبيانات الديناميكية
  if (event.request.url.includes('/icons/') || 
      event.request.url.includes('.js') || 
      event.request.url.includes('.css') || 
      event.request.url.includes('.html') ||
      event.request.url.includes('manifest.json')) {
    // استراتيجية Cache First للموارد الثابتة
    event.respondWith(
      caches.match(event.request)
        .then(response => {
          return response || fetch(event.request)
            .then(fetchResponse => {
              // تخزين النسخة الجديدة في الكاش
              if (fetchResponse.status === 200) {
                const clonedResponse = fetchResponse.clone();
                caches.open(CACHE_NAME).then(cache => {
                  cache.put(event.request, clonedResponse);
                });
              }
              return fetchResponse;
            });
        })
        .catch(() => {
          // في حالة الفشل، محاولة استرجاع الصفحة الرئيسية
          if (event.request.url.includes('.html')) {
            return caches.match('./index.html');
          }
          return new Response('حدث خطأ في الاتصال', { status: 408, headers: { 'Content-Type': 'text/plain' } });
        })
    );
  } else {
    // استراتيجية Network First للبيانات الديناميكية
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // نسخ الاستجابة قبل استهلاكها
          const responseClone = response.clone();
          
          // تخزين الاستجابة في الكاش للاستخدام في وضع عدم الاتصال
          if (response.status === 200) {
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseClone);
            });
          }
          
          return response;
        })
        .catch(() => {
          // في حال عدم وجود اتصال بالشبكة، محاولة الاسترجاع من الكاش
          return caches.match(event.request);
        })
    );
  }
});
