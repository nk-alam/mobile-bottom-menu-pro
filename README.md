# Mobile Bottom Menu Pro - WordPress Plugin

A comprehensive mobile bottom menu plugin with WooCommerce support, animated icons, and sticky product actions for mobile devices.

## Features

- 🎨 **Beautiful Design Styles**: Modern, Minimal, Classic, and Gradient themes
- 📱 **Mobile Optimized**: Perfect for mobile devices with responsive design
- 🛒 **WooCommerce Integration**: Sticky product bar with add to cart functionality
- ⚡ **Smooth Animations**: Customizable hover effects and micro-interactions
- 🎯 **Elementor Support**: Full Elementor widget for easy customization
- 🎨 **Color Customization**: Custom colors for branding
- 🔧 **Easy Configuration**: Simple admin panel for setup

## Installation

1. Upload the plugin files to `/wp-content/plugins/mobile-bottom-menu-pro/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings > Mobile Bottom Menu to configure

## How to Customize with Elementor

### Method 1: Using the Elementor Widget

1. **Install Elementor** (if not already installed)
2. **Edit any page with Elementor**
3. **Search for "Mobile Bottom Menu"** in the widget panel
4. **Drag and drop** the widget to your page
5. **Customize the design**:
   - Add/remove menu items
   - Change icons (Font Awesome or custom)
   - Set colors and styles
   - Adjust sizes and spacing
   - Enable/disable animations

### Method 2: Global Settings

1. Go to **WordPress Admin > Settings > Mobile Bottom Menu**
2. Configure:
   - **Menu Items**: Add labels, icons, and URLs
   - **Design Style**: Choose from Modern, Minimal, Classic, or Gradient
   - **Colors**: Set primary and background colors
   - **Animations**: Enable smooth hover effects

## Design Styles

### 🎨 Modern Style
- Rounded corners with subtle shadows
- Smooth hover animations
- Glass-morphism effect
- Perfect for contemporary websites

### ✨ Minimal Style
- Clean, flat design
- Simple border separators
- Subtle hover effects
- Great for minimalist themes

### 🏛️ Classic Style
- Traditional button styling
- Solid color hover states
- Clean typography
- Ideal for business websites

### 🌈 Gradient Style
- Beautiful gradient backgrounds
- Vibrant color schemes
- Eye-catching animations
- Perfect for creative sites

## Customization Guide

### Adding Custom Icons

1. **Font Awesome Icons**: Use classes like `fas fa-home`, `fas fa-shopping-cart`
2. **Custom Icons**: Upload SVG icons or use icon fonts
3. **Elementor Icons**: Use the built-in icon picker in Elementor widget

### Color Customization

```css
/* Custom CSS for advanced styling */
:root {
    --mbm-primary-color: #your-color;
    --mbm-background-color: #your-bg-color;
    --mbm-text-color: #your-text-color;
}
```

### Advanced Styling

Add custom CSS in **Appearance > Customize > Additional CSS**:

```css
/* Custom hover effects */
.mbm-menu-item:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Custom gradient background */
.mbm-style-custom {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
}

/* Pulse animation for active items */
.mbm-menu-item.active {
    animation: pulse 2s infinite;
}
```

## WooCommerce Features

- **Sticky Product Bar**: Appears on single product pages
- **Quantity Selector**: Easy quantity adjustment
- **Variation Support**: Handles variable products
- **AJAX Add to Cart**: Smooth cart additions
- **Mobile Optimized**: Perfect for mobile shopping

## Responsive Design

The plugin automatically adapts to different screen sizes:
- **Desktop**: Hidden by default (mobile-first approach)
- **Tablet**: Optimized spacing and sizing
- **Mobile**: Full functionality with touch-friendly buttons

## Browser Support

- ✅ Chrome (Android & iOS)
- ✅ Safari (iOS)
- ✅ Firefox Mobile
- ✅ Samsung Internet
- ✅ Edge Mobile

## Performance

- **Lightweight**: Minimal CSS and JavaScript
- **Optimized**: Efficient animations using CSS transforms
- **Fast Loading**: Compressed assets and optimized code

## Troubleshooting

### Menu Not Showing
1. Check if you're viewing on mobile device
2. Ensure menu items are configured
3. Verify the plugin is activated

### Styling Issues
1. Clear cache (if using caching plugins)
2. Check for theme conflicts
3. Ensure proper CSS loading order

### WooCommerce Issues
1. Verify WooCommerce is active
2. Check product page settings
3. Test with default theme

## Support

For support and customization requests, please contact the plugin developer.

## Changelog

### Version 1.0.0
- Initial release
- Basic menu functionality
- WooCommerce integration
- Elementor widget support
- Multiple design styles
- Color customization
- Animation system