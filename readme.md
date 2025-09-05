# Secure PHP File Delivery

This project provides a **secure PHP script** for serving files to authenticated users.  
It protects against directory traversal attacks, enforces session login, detects MIME types, and streams files safely to the browser.

---

## ✨ Features

- 🔒 **Session-protected access** – only logged-in users can download.
- 🛡 **Path traversal protection** – prevents `../../` style attacks using `realpath()`.
- 📂 **Configurable base storage** – restricts access to a specific folder.
- 📝 **MIME type detection** – uses `finfo` to set the correct `Content-Type`.
- 📎 **Flexible content disposition** – supports `inline` (preview in browser) or `attachment` (force download).
- 🚀 **Efficient streaming** – uses `readfile()` to avoid memory bloat with large files.
- ✅ **Safe headers** – sets cache control, length, and UTF-8 filename support.

---

## 🔧 Configuration

Edit the base storage directory in the script:

```php
$storage = "//fuga-store/storage/";
```

All file requests will be restricted to this folder.

---

## 📜 Usage

1. Ensure the user is logged in and session contains:

   ```php
   $_SESSION["status"] = "logged in";
   ```

2. Call the script with query parameters:

   - **file** (required): relative path inside storage  
   - **disposition** (optional): `inline` (default) or `attachment`

   **Examples:**

   - Display an image in browser:
     ```php
     download.php?file=www/users/1/logo.png
     ```

   - Force file download:
     ```php
     download.php?file=docs/report.pdf&disposition=attachment
     ```

---

## 🛡 Security Notes

- Files outside `$storage` cannot be accessed.  
- Case-insensitive check (`stripos()`) ensures Windows/SMB shares work correctly.  
- Only valid files (`is_file()`) are served; directories and invalid paths are rejected.  
- Headers prevent caching and leaking sensitive content.

---

## 📂 Headers Sent

- `Content-Type` → actual MIME (e.g. `image/png`, `application/pdf`).  
- `Content-Length` → file size in bytes.  
- `Content-Disposition` → `inline` or `attachment` with safe UTF-8 filename.  
- `Cache-Control`, `Pragma`, `Expires` → disable caching.  

---

## 🚀 Example Flow

1. User logs in → `$_SESSION["status"] = "logged in";`  
2. Browser requests:  
   ```php
   download.php?file=docs/report.pdf
   ```
3. Script validates:  
   - Path exists  
   - Path is inside storage  
   - Path is a file  
4. File streams with correct headers.  

---

## ⚠️ Limitations

- Does **not** support HTTP range requests (`206 Partial Content`) for resumable downloads.  
- Assumes storage is accessible and readable by PHP.  
- Very large files may require tuning PHP execution limits and web server settings.

---

## 📄 License

MIT License.  
Feel free to use and adapt this script in your own projects.
