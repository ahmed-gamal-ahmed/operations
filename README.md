# Product Scanner

A web-based product scanning and inventory management system.

## Features

- Barcode scanning and product tracking
- Excel file import/export
- Mobile-friendly interface
- Real-time product status updates
- Barcode editing and mapping
- Local storage for offline functionality

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/product-scanner.git
cd product-scanner
```

2. Set up the database:
- Create a new MySQL database named `product_scanner`
- Import the database schema (tables will be created automatically on first run)

3. Configure the database connection:
- Open `config.php`
- Update the database credentials if needed:
```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'product_scanner');
```

4. Set up the web server:
- Point your web server to the project directory
- Ensure PHP has write permissions for the directory

## Usage

1. Access the application through your web browser:
```
http://localhost/product-scanner/
```

2. Upload an Excel file containing product data:
- Click the menu button (☰)
- Use the "Upload Excel File" option
- The file should have columns including barcodes and product information

3. Start scanning:
- Enter barcodes manually or use a barcode scanner
- Scanned products will move from "Remaining Products" to "Scanned Products"
- Use the sidebar options to manage columns and export data

## Development

### Project Structure
```
product-scanner/
├── index.html          # Main application interface
├── config.php          # Database configuration
├── get_products.php    # API endpoint for products
├── get_scanned.php     # API endpoint for scanned items
├── save_products.php   # API endpoint for saving products
├── save_scanned.php    # API endpoint for saving scanned items
└── ...                 # Other PHP endpoints
```

### Testing
1. Start your local server
2. Access the application through your browser
3. Test the following features:
   - File upload
   - Barcode scanning
   - Product tracking
   - Data export
   - Mobile mode

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 