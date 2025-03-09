# El Arquitecto A.I. Styling Guide

This document outlines the styling system used throughout the El Arquitecto A.I. application. The system is designed to provide a consistent, modern, and engaging user interface with a focus on dark mode aesthetics.

## Theme Components

The application's styling is built using reusable components located in `resources/js/components/theme/`:

### 1. NeonEffects.vue
Base component that provides core animations and effects:
- Sliding animations (right, left, up, down)
- Glow and pulse effects
- Glass effect styles
- Neon border utilities

### 2. NeonCorners.vue
Provides corner accent effects with the following features:
- Customizable positions (top-left, top-right, bottom-left, bottom-right, all)
- Color variants (primary, secondary, accent, cyan)
- Size options (sm, md, lg)
- Glowing animation effects

### 3. NeonBorders.vue
Implements animated neon borders with:
- Position options (top, right, bottom, left, all)
- Color variants (primary, secondary, accent, cyan, gradient)
- Animation types (slide-right, slide-left, slide-up, slide-down, none)
- Customizable opacity

### 4. FloatingNeonLines.vue
Creates background decoration with floating neon lines:
- Multiple variants (default, dense, sparse)
- Adjustable opacity
- Responsive design (hidden on mobile)

### 5. GlassContainer.vue
Provides glass-effect containers with:
- Variant options (default, dark, light)
- Optional neon borders and corners
- Customizable rounding and padding
- Backdrop blur effects

### 6. SearchBar.vue
Implements an animated search bar with:
- Two variants (default, spotlight)
- Neon effects on focus
- Glass effect background
- Animated borders and corners

## Usage Guidelines

### Dark Mode Styling
The neon effects and animations are specifically designed for dark mode. When implementing new pages or components:

1. **Color Palette**
   - Primary: Neon purple (#7C3AED)
   - Secondary: Neon pink (#EC4899)
   - Accent: Neon blue (#60A5FA)
   - Cyan: Neon cyan (#22D3EE)

2. **Background**
   - Base: Dark background (bg-background)
   - Glass Effect: Semi-transparent overlays with blur
   - Floating Lines: Subtle animated accents

3. **Containers**
   - Use `GlassContainer` for card-like elements
   - Apply neon borders for emphasis
   - Add corner accents for visual interest

4. **Interactive Elements**
   - Implement hover effects with glow
   - Use animated borders on focus
   - Add transition effects for smooth interactions

### Implementation Example

```vue
<template>
  <div class="dark-theme">
    <!-- Background Effects -->
    <FloatingNeonLines variant="default" />

    <!-- Container with Glass Effect -->
    <GlassContainer
      variant="default"
      withBorders
      withCorners
      rounded="lg"
      padding="md"
    >
      <!-- Content -->
    </GlassContainer>

    <!-- Search Functionality -->
    <SearchBar
      variant="default"
      placeholder="Search..."
    />
  </div>
</template>
```

## Best Practices

1. **Performance**
   - Use `pointer-events: none` for decorative elements
   - Implement will-change for smooth animations
   - Limit the number of animated elements

2. **Accessibility**
   - Ensure sufficient contrast ratios
   - Provide focus indicators
   - Include ARIA labels where needed

3. **Responsive Design**
   - Hide complex animations on mobile
   - Adjust glass effect intensity for performance
   - Scale neon effects appropriately

4. **Maintainability**
   - Use theme components consistently
   - Follow the established color system
   - Maintain dark mode specificity

## Adding New Pages

When creating new pages:

1. Import necessary theme components
2. Apply dark mode classes
3. Use glass containers for content sections
4. Add floating lines for background interest
5. Implement neon effects for interactive elements
6. Ensure consistent spacing and layout

Remember to test the implementation in both dark and light modes, although the neon effects are primarily designed for dark mode.