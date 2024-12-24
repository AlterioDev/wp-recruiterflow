# WP Recruiterflow 💼

WordPress plugin to sync job vacancies from Recruiterflow into your WordPress site. 🔄

## Features 🌟

- Sync vacancies from Recruiterflow
- Custom post type for vacancies
- Configurable vacancy slug/URL structure
- Detailed vacancy information display
- API key management
- Custom vacancy templates
- Clean post type integration
- Translation-ready 🌍

## Requirements 📋

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Recruiterflow API credentials 🔑

## Installation 🚀

1. Upload to `/wp-content/plugins/`
2. Activate the plugin
3. Go to Settings > WP Recruiterflow ⚙️
4. Enter your Recruiterflow API key
5. Configure vacancy settings
6. Run initial sync

## Configuration ⚙️

### API Setup 🔌

1. Get API key from Recruiterflow
2. Enter in plugin settings
3. Test connection

### Vacancy Settings 📝

- Custom URL structure
- Display options
- Sync frequency

## Templates 🎨

Override default templates by copying them to your theme:

```
your-theme/
└── wp-recruiterflow/
    └── public/
        └── vacancy/
            ├── single.php
            └── archive.php
```

## Development 👨‍💻

Built with modern PHP practices:

- PSR-4 autoloading
- OOP architecture
- WordPress coding standards
- Custom template system

### Building 🔧

```bash
composer install
```

## License 📄

MIT. See [License File](LICENSE.md) for more information.

## Credits 👏

Built by [Alterio](https://alterio.nl)
