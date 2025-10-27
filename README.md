# Remote Media Library

A WordPress must-use plugin that replaces local media URLs with production URLs, allowing developers to work in local environments without downloading all media files.

## Description

Remote Media Library automatically rewrites URLs pointing to `wp-content/uploads` on local development environments to point to your production site instead. This eliminates the need to sync gigabytes of media files to your local machine while developing.

## Features

- Automatically detects local development environments
- Replaces media URLs in real-time before page output
- Zero configuration after initial setup
- No database tables or stored options
- Skips admin pages to keep WordPress media library functional
- Lightweight with minimal performance impact
- Must-use plugin - always active, cannot be accidentally deactivated

## Requirements

- WordPress 4.0 or higher
- PHP 7.4 or higher

## Installation

As a must-use plugin, installation is simple:

1. Copy `0000-remote-media-library.php` to your WordPress `mu-plugins` directory:
   ```bash
   cp 0000-remote-media-library.php wp-content/mu-plugins/
   ```

   Or if using a subdirectory structure:
   ```bash
   mkdir -p wp-content/mu-plugins/remote-media-library
   cp 0000-remote-media-library.php wp-content/mu-plugins/remote-media-library/
   ```

2. Configure the production URL (see Configuration section below)

**Note**: Must-use plugins are automatically activated and cannot be deactivated through the WordPress admin interface. They also don't appear in the regular plugins list.

## Configuration

Edit `0000-remote-media-library.php` and update the following constants:

```php
const LIVE_URL      = 'https://example.com';  // Your production site URL
const LOCAL_URLS    = [ '.local', '.test', '.dev', '.box' ];  // Local domain patterns
const PATH_TO_CHECK = '/wp-content/uploads/';  // Upload directory path
```

### Configuration Options

- **LIVE_URL**: The full URL of your production site (no trailing slash)
- **LOCAL_URLS**: Array of strings to detect local environments. The plugin activates if the home URL contains any of these strings.
- **PATH_TO_CHECK**: The path to your uploads directory (default: `/wp-content/uploads/`)

## How It Works

1. The plugin checks if the site is running in a local environment by matching the home URL against `LOCAL_URLS` patterns
2. On the `template_redirect` hook, if the site is local and not in admin, output buffering begins
3. During output buffering, a callback function processes the HTML content
4. All instances of your local uploads URL are replaced with the production uploads URL
5. The callback also handles escaped versions of URLs (for JSON, etc.)
6. The modified HTML is sent to the browser

## Use Cases

This plugin is ideal for:

- Development teams working with sites that have large media libraries
- Agencies managing multiple client sites with extensive image galleries
- Developers with limited local storage or bandwidth
- Teams wanting faster local environment setup

## Limitations

- Only works on the frontend (admin is intentionally excluded)
- Requires production site to be publicly accessible
- May have minor performance impact on pages with large HTML output
- Does not handle media uploads from local environments

## License

GPL-2.0+

## Author

Andrew Miller & 84EM
[https://84em.com](https://84em.com)