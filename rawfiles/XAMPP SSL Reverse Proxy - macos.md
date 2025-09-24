#### Apache (XAMPP) SSL Reverse Proxy using MacOS

1. Generate/Prepare SSL Certificate For local development, you can use a self-signed cert:
    - Create a folder to store your certificate
        ```bash
        mkdir -p ~/certs
        cd ~/certs
        ```
    - Generate a private key for **xampp**
        ```bash
        mkdir -p ~/certs
        cd ~/certs

        # Generate private key
        openssl genrsa -out localhost.key 2048

        # Create CSR
        openssl req -new -key localhost.key -out localhost.csr -subj "/CN=localhost"

        # Create SAN config
        cat > localhost.ext <<EOL
        subjectAltName = DNS:localhost, IP:127.0.0.1
        EOL

        # Generate self-signed certificate valid for 365 days
        openssl x509 -req -in localhost.csr -signkey localhost.key -out localhost.crt -days 365 -extfile localhost.ext

        ```
    - Generate a private key for **postman**
        ```bash
        cd ~/certs

        # Convert CRT to PEM (if not already)
        openssl x509 -in localhost.crt -out localhost.pem

        # Convert key to PEM (if not already)
        openssl rsa -in localhost.key -out localhost.key.pem
        ```
    - Trust the certificate on macOS
        - Open Keychain Access → drag localhost.crt into System keychain.
        - Double-click the cert → expand Trust → set “Always Trust”.
        - Close → enter password if prompted → restart browser.
2. Open Apache SSL config:
    ```swift
    /Applications/XAMPP/xamppfiles/etc/extra/httpd-ssl.conf
    ```
3. Add a **VirtualHost**:
    ```apache
    <VirtualHost *:443>
        ServerName localhost

        SSLEngine on
        SSLCertificateFile "/Users/limvichet/Documents/Apps/freelancer-backend/certs/localhost.crt"
        SSLCertificateKeyFile "/Users/limvichet/Documents/Apps/freelancer-backend/certs/localhost.key"

        # 🔹 Tell Apache to talk to Laravel via HTTP (not SSL!)
        ProxyPreserveHost On
        ProxyRequests Off
        SSLProxyEngine Off

        ProxyPass        / http://127.0.0.1:8000/
        ProxyPassReverse / http://127.0.0.1:8000/
    </VirtualHost>
    ```
4. Make sure required modules are enabled in httpd.conf:
    ```apache
    LoadModule ssl_module modules/mod_ssl.so
    LoadModule proxy_module modules/mod_proxy.so
    LoadModule proxy_http_module modules/mod_proxy_http.so
    ```
5. Restart Apache:
    ```bash
    sudo /Applications/XAMPP/xamppfiles/xampp restart
    ```
6. Start your Laravel dev server:
    ```bash
    php artisan serve --host=127.0.0.1 --port=8000
    ```