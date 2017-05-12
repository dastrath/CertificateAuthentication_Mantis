# Certificate Authentication Plugin

This is a certificate authentication plugin which allows you to login to Mantis using your x.509 certificate

The authentication mechanism implemented by this plugin works as follows:
- If user is not registered in the db, user standard behavior.
- If the email-adress or nickname does not match the user-account from certificate, use standard behaviour.
- Otherwise, auto-signin the user without a password.

Even if users beigng auto-signed in, then can manage or use passwords that are stored in the MantisBT database.

This allows a login to Mantis in case of using another machine or if the certificate expired.

## Usage

An automatic login using a certificate is not possible. You need to know your username or (if configured) email-adress stored within Mantis database.

For authentication the first email-adress within DN-field is used.

To login you simply try to login using your normal username or (if configured) email-adress. If the mantis-account matches the one selected by the client-certificate no password-page will be displayed: You will directly be logged in to Mantis.

If the account does not match, you'll get the normal password-page to login using another mantis-account.

## Requirements

You need to enable the use of a client certificate for your webserver (e.g. Apache or Nginx).

Nginx:

        ssl_verify_client optional;

        ssl_client_certificate /etc/nginx/certs/cas.pem;

        fastcgi_param TLS_SUCCESS $ssl_client_verify;

        fastcgi_param TLS_DN      $ssl_client_s_dn;

Apache:

        ToDo

## Screenshots

Native Login Page for Username

![Login Page](doc/native_login_form_for_username.png "Native Login Page")

Native Credentials Page for Password (skipped for certificate login)

![Credentials Page](doc/native_credentials_page.png "Native Credentials Page")

## Dependencies
MantisBT v2.3.0-dev once auth plugin support is added.

