# 🚀 Deployment Guide

## 1. FTP / File Transfer Exclusion List

When uploading files from this repository to your live web server, you should **exclude** development, documentation, and system files to maintain security and cleanliness.

Configure your FTP client (e.g., FileZilla Filters) to ignore the following:

### Version Control & Dev Tools
```text
.git/
.gemini/
.github/
.gitignore
.editorconfig
```

### Documentation & Source Assets
```text
docs/
tests/
LICENSE
README.md
CHANGELOG.md
```

### Database & Security
```text
db/
*\db_creds-sample.php
```

### System Junk
```text
*\thumbs.db
\System Volume Information\
\$Recycle.Bin\
\RECYCLE?\
\Recovery\
```

## 2. Server Configuration

1.  **Upload**: Upload the `core-cms` files (excluding the list above).
2.  **Config**: Ensure `config/db_creds.php` exists on the server.
    *   *Do not overwrite this file if you are just updating code.*
3.  **Permissions**:
    *   Directories: `755`
    *   Files: `644`
    *   `config/db_creds.php`: `600` (Strict) or `644`.