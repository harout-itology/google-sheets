# Laravel Moving Average Calculator

A Laravel application to calculate the moving average of daily website visitors using data from Google Sheets.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)

## Requirements

1. **PHP >= 8.0**
2. **Composer**
3. **A Google Cloud account with the Sheets API enabled**
4. **A service account key from Google Cloud**

## Installation

1. **Clone the Repository**
    ```bash
    git clone https://github.com/harout-itology/google-sheets.git
    cd google-sheets
    ```

2. **Install Dependencies**
    ```bash
    composer install
    ```

3. **Set up Environment File**
    - Copy the `.env.example` file to create your own `.env` file.
    ```bash
    cp .env.example .env
    ```

4. **Update the .env file with relevant information**
    - change the path to your Google Cloud service account key
    ```bash
    GOOGLE_SERVICE_ACCOUNT=path_to_your_service_account_credentials.json
    ```

## Configuration

- Generate an application key:
    ```bash
    php artisan key:generate
    ```

- Start the Laravel development server:
    ```bash
    php artisan serve
    ```

The application should now be accessible at `http://localhost:8000`. but if the address already in use, the server will run on `http://localhost:8001`.

## Usage

1. Run the command:
   - Use the following command to calculate the moving average for a given Google Sheet:
       ```bash
       php artisan calculate:average YOUR_GOOGLE_SHEET_ID
       ```
2. Replace YOUR_GOOGLE_SHEET_ID with the ID of your Google Sheet.


## Testing

To run the unit tests:

```bash
php artisan test
```
