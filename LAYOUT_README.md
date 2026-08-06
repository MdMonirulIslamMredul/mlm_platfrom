# Laravel Layout Structure

## File Organization

### Layout Files (resources/views/layouts/)

- **app.blade.php** - Main layout template
- **header.blade.php** - Header with navigation
- **footer.blade.php** - Footer with contact info and newsletter

## Usage

### Creating a New Page

To create a new page using the layout system:

```blade
@extends('layouts.app')

@section('title', 'Your Page Title - Canada Visa Processing')

{{-- Optional: Add page-specific CSS --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/your-page.css') }}">
@endpush

@section('content')
    {{-- Your page content goes here --}}
    <div class="container">
        <h1>Your Content</h1>
    </div>
@endsection

{{-- Optional: Add page-specific JavaScript --}}
@push('scripts')
    <script src="{{ asset('js/your-page.js') }}"></script>
@endpush
```

### Features

#### Header
- Automatic active navigation highlighting
- Responsive design (desktop & mobile)
- Logo linking to homepage
- Check Visa Status button

#### Footer
- Company information with logo
- Contact details (with clickable links)
- Newsletter subscription form
- Social media links
- Copyright year (auto-updates)

## Layout Sections

### @section('title')
Sets the page title. Default: 'Canada Visa Processing'

### @section('content')
Main content area for your page

### @push('styles')
Add page-specific CSS files

### @push('scripts')
Add page-specific JavaScript files

## Navigation

The header automatically highlights the active page based on the current URL:
- Home: `/`
- Contact: `/contact`
- About: `/about`

## Examples

### Home Page (index.blade.php)
```blade
@extends('layouts.app')
@section('title', 'Home - Canada Visa Processing')
@section('content')
    <!-- Home content -->
@endsection
```

### Contact Page (contact.blade.php)
```blade
@extends('layouts.app')
@section('title', 'Contact Us - Canada Visa Processing')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush
@section('content')
    <!-- Contact content -->
@endsection
@push('scripts')
    <script src="{{ asset('js/contact.js') }}"></script>
@endpush
```

### About Page (about.blade.php)
```blade
@extends('layouts.app')
@section('title', 'About Us - Canada Visa Processing')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush
@section('content')
    <!-- About content -->
@endsection
@push('scripts')
    <script src="{{ asset('js/about.js') }}"></script>
@endpush
```

## Customization

### Modifying Header
Edit `resources/views/layouts/header.blade.php`

### Modifying Footer
Edit `resources/views/layouts/footer.blade.php`

### Modifying Main Layout
Edit `resources/views/layouts/app.blade.php`

## Assets

### Global Assets (Loaded on all pages)
- Bootstrap 5.0.2
- Bootstrap Icons 1.11.2
- Custom CSS: `static/home/style.css`, `css/custom.css`
- Main JavaScript: `js/main.js`

### Page-Specific Assets
Use `@push('styles')` and `@push('scripts')` in individual pages

## Benefits

✅ DRY (Don't Repeat Yourself) - Write header/footer once
✅ Easy maintenance - Update all pages by editing one file
✅ Consistent design across all pages
✅ Clean, organized code
✅ Easy to add new pages
✅ SEO-friendly page titles
✅ Automatic active navigation
