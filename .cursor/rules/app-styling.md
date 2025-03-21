# El Arquitecto A.I. Styling Guide

This document outlines the styling system used throughout the El Arquitecto A.I. application. The system is designed to provide a consistent, modern, and engaging user interface with a focus on dark mode aesthetics inspired by cyberpunk and neon visual language.

## Core Design Philosophy

El Arquitecto A.I. employs a futuristic cyberpunk aesthetic characterized by:

1. **Dark Backgrounds with Neon Accents**: The application uses deep, dark backgrounds with vibrant neon elements that create visual interest and guide user attention.

2. **Glass Morphism**: Translucent, blurred containers that create depth and a sense of futuristic interfaces.

3. **Animated Elements**: Subtle animations including glowing effects, sliding neon lines, and pulsing elements that create a sense of a living, dynamic interface.

4. **Geometric Accents**: Angular corners, grid patterns, and linear elements that reinforce the technological aesthetic.

5. **Cyberpunk Typography**: Text with subtle glow effects, especially for headings and important UI elements.

## Theme Components

The application's styling is built using reusable components located in `resources/js/components/theme/`:

### 1. NeonEffects.vue
Base component that provides core animations and effects:
- Sliding animations (right, left, up, down) with regular and slow variants
- Glow and pulse effects with customizable timing
- Glass effect styles with backdrop blur and subtle borders
- Text glow utilities with varying intensities (light, medium, strong)
- Neon border utilities with customizable colors
- Cyberpunk grid background patterns

### 2. NeonCorners.vue
Provides corner accent effects with the following features:
- Customizable positions (top-left, top-right, bottom-left, bottom-right, all)
- Color variants (primary, secondary, accent, cyan)
- Size options (sm, md, lg)
- Glowing animation effects with pulsing opacity
- Gradient transitions from color to transparent

### 3. NeonBorders.vue
Implements animated neon borders with:
- Position options (top, right, bottom, left, all)
- Color variants (primary, secondary, accent, cyan, gradient)
- Animation types (slide-right, slide-left, slide-up, slide-down, none)
- Customizable opacity and thickness
- Gradient options for multi-color effects

### 4. FloatingNeonLines.vue
Creates background decoration with floating neon lines:
- Multiple variants (default, dense, sparse)
- Adjustable opacity for subtle to prominent effects
- Responsive design (hidden on mobile by default)
- Animated lines that slide across the interface
- Strategic positioning to create depth and visual interest

### 5. GlassContainer.vue
Provides glass-effect containers with:
- Variant options (default, dark, light)
- Optional neon borders and corners
- Customizable rounding and padding
- Backdrop blur effects
- Border color options (primary, secondary, accent, cyan, white)

### 6. FormInput.vue
Styled form input component with:
- Animated focus states with neon glow
- Error state handling
- Consistent styling with the application theme
- Accessibility features
- Responsive design

### 7. FormTextarea.vue
Styled textarea component with:
- Consistent styling with other form elements
- Animated focus states
- Error display
- Customizable rows
- Responsive design

### 8. FormButton.vue
Styled button component with:
- Multiple variants (primary, secondary, outline, text)
- Size options (sm, md, lg)
- Hover and focus states with neon glow effects
- Disabled state styling
- Consistent with the application's cyberpunk aesthetic

## Color System

The application uses a carefully selected color palette that reinforces the cyberpunk aesthetic:

1. **Primary**: Neon purple (#7C3AED) - Used for primary actions, important UI elements, and key accents
2. **Secondary**: Neon pink (#EC4899) - Used for secondary actions and complementary accents
3. **Accent**: Neon blue (#60A5FA) - Used for tertiary elements and additional visual interest
4. **Cyan**: Neon cyan (#22D3EE) - Used for highlights and special accents
5. **Background**: Deep dark (#111827) - The base background color
6. **Foreground**: Light text (#F9FAFB) - The primary text color
7. **White with opacity**: Used for borders and subtle separators

## Animation System

Animations are a key part of the cyberpunk aesthetic and include:

1. **Neon Sliding**: Lines that slide across the interface, creating movement and visual interest
   - Horizontal slides (left-to-right, right-to-left)
   - Vertical slides (top-to-bottom, bottom-to-top)
   - Varying speeds (regular and slow)

2. **Glowing Effects**: Elements that pulse with varying opacity
   - Subtle pulsing for background elements
   - More pronounced glowing for interactive elements
   - Text glow for headings and important information

3. **Focus Animations**: Interactive elements that animate on focus/hover
   - Expanding borders
   - Increasing glow intensity
   - Color transitions

4. **Loading Animations**: Custom loading indicators
   - Spinning elements with neon trails
   - Pulsing containers
   - Progress indicators with neon accents

## Layout Patterns

The application employs several consistent layout patterns:

1. **Glass Containers**: Content sections with translucent backgrounds and blur effects
2. **Corner Accents**: Decorative corner elements that frame important sections
3. **Floating Lines**: Background decorations that create depth and movement
4. **Grid Layouts**: Structured content organization with cyberpunk-inspired spacing
5. **Neon Separators**: Section dividers with animated neon effects

## Usage Guidelines

### Dark Mode Styling
The neon effects and animations are specifically designed for dark mode. When implementing new pages or components:

1. **Background Layers**
   - Base: Dark background (bg-background)
   - Middle: Glass effect containers with varying opacity
   - Top: Interactive elements and content
   - Decoration: Floating lines and corner accents

2. **Content Hierarchy**
   - Use text glow for headings (text-glow-medium)
   - Apply glass containers for content sections
   - Implement neon borders for emphasis
   - Add corner accents for visual interest

3. **Interactive Elements**
   - Apply hover effects with increasing glow intensity
   - Use animated borders on focus
   - Implement transition effects for state changes
   - Ensure sufficient contrast for accessibility

### Implementation Example

```vue
<template>
  <div class="min-h-screen bg-background text-foreground">
    <!-- Background Effects -->
    <FloatingNeonLines variant="default" opacity="0.2" />

    <!-- Page Header -->
    <h1 class="text-glow-medium text-center text-2xl font-bold text-primary">
      Page Title
    </h1>

    <!-- Content Container -->
    <GlassContainer
      variant="default"
      withBorders
      withCorners
      rounded="xl"
      padding="lg"
    >
      <!-- Form Elements -->
      <FormInput
        v-model="form.field"
        label="Field Label"
        :error="errors?.field"
      />

      <!-- Action Buttons -->
      <div class="flex justify-end gap-x-4">
        <FormButton variant="text">
          Cancel
        </FormButton>
        <FormButton variant="primary">
          Submit
        </FormButton>
      </div>
    </GlassContainer>
  </div>
</template>
```

## Best Practices

1. **Performance Optimization**
   - Use `pointer-events: none` for decorative elements
   - Implement `will-change` for smooth animations
   - Limit the number of animated elements per view
   - Use opacity to reduce visual weight of background elements

2. **Accessibility Considerations**
   - Ensure sufficient contrast ratios (minimum 4.5:1)
   - Provide clear focus indicators
   - Include ARIA labels for decorative elements
   - Test with screen readers and keyboard navigation

3. **Responsive Design**
   - Hide complex animations on mobile devices
   - Adjust glass effect intensity for performance
   - Scale neon effects appropriately for different screen sizes
   - Ensure touch targets are sufficiently large (minimum 44x44px)

4. **Maintainability**
   - Use theme components consistently
   - Follow the established color system
   - Maintain dark mode specificity
   - Document custom implementations

## Adding New Pages

When creating new pages:

1. Import necessary theme components
2. Apply the dark background class
3. Use glass containers for content sections
4. Add floating lines for background interest
5. Implement neon effects for interactive elements
6. Ensure consistent spacing and layout
7. Test on various screen sizes and devices

The cyberpunk aesthetic should be consistent throughout the application, creating an immersive and engaging user experience that reinforces the futuristic, AI-focused brand identity of El Arquitecto A.I.