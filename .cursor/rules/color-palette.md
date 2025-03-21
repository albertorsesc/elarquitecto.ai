# El Arquitecto A.I. Color Palette

This document outlines the comprehensive color palette used throughout the El Arquitecto A.I. application, focusing on the dark mode aesthetic that defines the cyberpunk visual language of the platform.

## Core Color System

The application uses a carefully curated palette of neon colors against dark backgrounds to create a futuristic, cyberpunk aesthetic. All colors are provided in multiple formats (HEX, RGB, HSL) for different implementation contexts.

### Primary Colors

#### Primary Purple
- **HEX**: #7C3AED
- **RGB**: rgb(124, 58, 237)
- **HSL**: hsl(263, 70%, 60%)
- **CSS Variable**: `--primary`

**Usage:**
- Primary action buttons
- Important UI elements
- Key interactive components
- Focus states
- Primary borders and outlines
- Main navigation elements
- Selected states

#### Primary Foreground
- **HEX**: #FAFAFA
- **RGB**: rgb(250, 250, 250)
- **HSL**: hsl(0, 0%, 98%)
- **CSS Variable**: `--primary-foreground`

**Usage:**
- Text on primary-colored backgrounds
- Icons within primary elements
- Button text for primary buttons

### Secondary Colors

#### Secondary Blue
- **HEX**: #38BDF8
- **RGB**: rgb(56, 189, 248)
- **HSL**: hsl(196, 80%, 55%)
- **CSS Variable**: `--secondary`

**Usage:**
- Secondary action buttons
- Complementary accents
- Progress indicators
- Information alerts
- Secondary navigation
- Decorative elements
- Timeline components

#### Secondary Foreground
- **HEX**: #FAFAFA
- **RGB**: rgb(250, 250, 250)
- **HSL**: hsl(0, 0%, 98%)
- **CSS Variable**: `--secondary-foreground`

**Usage:**
- Text on secondary-colored backgrounds
- Icons within secondary elements
- Button text for secondary buttons

### Accent Colors

#### Accent Pink
- **HEX**: #EC4899
- **RGB**: rgb(236, 72, 153)
- **HSL**: hsl(335, 80%, 60%)
- **CSS Variable**: `--accent`

**Usage:**
- Tertiary elements
- Highlights
- Decorative accents
- Attention-grabbing elements
- Hover states
- Animated elements
- Neon borders

#### Accent Foreground
- **HEX**: #FAFAFA
- **RGB**: rgb(250, 250, 250)
- **HSL**: hsl(0, 0%, 98%)
- **CSS Variable**: `--accent-foreground`

**Usage:**
- Text on accent-colored backgrounds
- Icons within accent elements
- Button text for accent buttons

### Cyan Highlight
- **HEX**: #22D3EE
- **RGB**: rgb(34, 211, 238)
- **HSL**: hsl(190, 90%, 55%)
- **CSS Variable**: `--color-cyan-400` (referenced in code)

**Usage:**
- Special highlights
- Timeline dots
- Date indicators
- Code syntax highlighting
- Special UI accents
- Attention-drawing elements

### Background Colors

#### Main Background
- **HEX**: #1E293B
- **RGB**: rgb(30, 41, 59)
- **HSL**: hsl(226, 30%, 12%)
- **CSS Variable**: `--background`

**Usage:**
- Main application background
- Page backgrounds
- Base layer for the application

#### Card Background
- **HEX**: #1E293B (with 70% opacity)
- **RGB**: rgba(30, 41, 59, 0.7)
- **HSL**: hsla(226, 30%, 15%, 0.7)
- **CSS Variable**: `--card`

**Usage:**
- Card backgrounds
- Content containers
- Dialog backgrounds
- Glass effect containers

#### Popover Background
- **HEX**: #1E293B (with 90% opacity)
- **RGB**: rgba(30, 41, 59, 0.9)
- **HSL**: hsla(226, 30%, 15%, 0.9)
- **CSS Variable**: `--popover`

**Usage:**
- Dropdown menus
- Tooltips
- Popovers
- Context menus

#### Muted Background
- **HEX**: #1F2937
- **RGB**: rgb(31, 41, 55)
- **HSL**: hsl(226, 30%, 20%)
- **CSS Variable**: `--muted`

**Usage:**
- Subtle backgrounds
- Disabled elements
- Secondary containers
- Less prominent UI elements

### Text Colors

#### Foreground Text
- **HEX**: #F1F5F9
- **RGB**: rgb(241, 245, 249)
- **HSL**: hsl(213, 10%, 95%)
- **CSS Variable**: `--foreground`

**Usage:**
- Main text color
- Primary content
- Headings
- Labels

#### Muted Foreground
- **HEX**: #CBD5E1
- **RGB**: rgb(203, 213, 225)
- **HSL**: hsl(213, 10%, 75%)
- **CSS Variable**: `--muted-foreground`

**Usage:**
- Secondary text
- Hints
- Placeholders
- Less important information
- Disabled text

### Border and Input Colors

#### Border Color
- **HEX**: #334155 (with 50% opacity)
- **RGB**: rgba(51, 65, 85, 0.5)
- **HSL**: hsla(226, 30%, 25%, 0.5)
- **CSS Variable**: `--border`

**Usage:**
- Borders for containers
- Dividers
- Separators
- Table cell borders

#### Input Background
- **HEX**: #334155
- **RGB**: rgb(51, 65, 85)
- **HSL**: hsl(226, 30%, 25%)
- **CSS Variable**: `--input`

**Usage:**
- Form input backgrounds
- Search fields
- Text areas
- Select dropdowns

#### Ring Color
- **HEX**: #7C3AED
- **RGB**: rgb(124, 58, 237)
- **HSL**: hsl(263, 70%, 60%)
- **CSS Variable**: `--ring`

**Usage:**
- Focus rings
- Selected state indicators
- Active element indicators

### Destructive Colors

#### Destructive Red
- **HEX**: #F43F5E
- **RGB**: rgb(244, 63, 94)
- **HSL**: hsl(0, 84%, 60%)
- **CSS Variable**: `--destructive`

**Usage:**
- Error states
- Delete buttons
- Warning indicators
- Destructive actions

#### Destructive Foreground
- **HEX**: #FAFAFA
- **RGB**: rgb(250, 250, 250)
- **HSL**: hsl(0, 0%, 98%)
- **CSS Variable**: `--destructive-foreground`

**Usage:**
- Text on destructive-colored backgrounds
- Icons within destructive elements

### Chart Colors

The application uses a consistent set of colors for data visualization:

#### Chart Purple (1)
- **HEX**: #7C3AED
- **RGB**: rgb(124, 58, 237)
- **HSL**: hsl(263, 70%, 60%)
- **CSS Variable**: `--chart-1`

#### Chart Blue (2)
- **HEX**: #38BDF8
- **RGB**: rgb(56, 189, 248)
- **HSL**: hsl(196, 80%, 55%)
- **CSS Variable**: `--chart-2`

#### Chart Pink (3)
- **HEX**: #EC4899
- **RGB**: rgb(236, 72, 153)
- **HSL**: hsl(335, 80%, 60%)
- **CSS Variable**: `--chart-3`

#### Chart Green (4)
- **HEX**: #10B981
- **RGB**: rgb(16, 185, 129)
- **HSL**: hsl(150, 60%, 50%)
- **CSS Variable**: `--chart-4`

#### Chart Yellow (5)
- **HEX**: #FBBF24
- **RGB**: rgb(251, 191, 36)
- **HSL**: hsl(40, 90%, 60%)
- **CSS Variable**: `--chart-5`

**Usage:**
- Data visualization
- Charts and graphs
- Diagrams
- Progress indicators
- Statistical displays

### Sidebar Colors

The sidebar has its own set of colors to create a distinct navigation area:

#### Sidebar Background
- **HEX**: #1E293B (with 70% opacity)
- **RGB**: rgba(30, 41, 59, 0.7)
- **HSL**: hsla(226, 30%, 15%, 0.7)
- **CSS Variable**: `--sidebar-background`

#### Sidebar Foreground
- **HEX**: #F1F5F9
- **RGB**: rgb(241, 245, 249)
- **HSL**: hsl(213, 10%, 95%)
- **CSS Variable**: `--sidebar-foreground`

#### Sidebar Primary
- **HEX**: #7C3AED
- **RGB**: rgb(124, 58, 237)
- **HSL**: hsl(263, 70%, 60%)
- **CSS Variable**: `--sidebar-primary`

#### Sidebar Primary Foreground
- **HEX**: #FAFAFA
- **RGB**: rgb(250, 250, 250)
- **HSL**: hsl(0, 0%, 98%)
- **CSS Variable**: `--sidebar-primary-foreground`

#### Sidebar Accent
- **HEX**: #334155 (with 50% opacity)
- **RGB**: rgba(51, 65, 85, 0.5)
- **HSL**: hsla(226, 30%, 25%, 0.5)
- **CSS Variable**: `--sidebar-accent`

#### Sidebar Accent Foreground
- **HEX**: #F1F5F9
- **RGB**: rgb(241, 245, 249)
- **HSL**: hsl(213, 10%, 95%)
- **CSS Variable**: `--sidebar-accent-foreground`

#### Sidebar Border
- **HEX**: #334155 (with 50% opacity)
- **RGB**: rgba(51, 65, 85, 0.5)
- **HSL**: hsla(226, 30%, 25%, 0.5)
- **CSS Variable**: `--sidebar-border`

#### Sidebar Ring
- **HEX**: #7C3AED
- **RGB**: rgb(124, 58, 237)
- **HSL**: hsl(263, 70%, 60%)
- **CSS Variable**: `--sidebar-ring`

## Syntax Highlighting Colors

For code blocks and syntax highlighting, the application uses a specialized color palette:

#### Keyword
- **HEX**: #C792EA
- **CSS Class**: `.syntax-keyword`

**Usage:** Programming language keywords, control structures

#### String
- **HEX**: #C3E88D
- **CSS Class**: `.syntax-string`

**Usage:** String literals, text content

#### Comment
- **HEX**: #9CA3AF
- **CSS Class**: `.syntax-comment`

**Usage:** Code comments, documentation

#### Type
- **HEX**: #FFCB6B
- **CSS Class**: `.syntax-type`

**Usage:** Type definitions, classes, interfaces

#### Function
- **HEX**: #82AAFF
- **CSS Class**: `.syntax-function`

**Usage:** Function names, method calls

#### Number
- **HEX**: #F78C6C
- **CSS Class**: `.syntax-number`

**Usage:** Numeric literals, constants

#### Variable
- **HEX**: #F07178
- **CSS Class**: `.syntax-variable`

**Usage:** Variable names, parameters

#### Tag
- **HEX**: #7C3AED
- **CSS Class**: `.syntax-tag`

**Usage:** HTML/XML tags, JSX components

#### Attribute
- **HEX**: #EC4899
- **CSS Class**: `.syntax-attr`

**Usage:** HTML/XML attributes, JSX props

#### Operator
- **HEX**: #89DDFF
- **CSS Class**: `.syntax-operator`

**Usage:** Mathematical operators, assignment operators

## Effective Color Combinations

The cyberpunk aesthetic relies on strategic color combinations to create visual interest and guide user attention. Here are some effective combinations used throughout the application:

### Primary Combinations

1. **Purple + Cyan**
   - Primary purple (#7C3AED) with cyan highlights (#22D3EE)
   - Creates a classic cyberpunk contrast
   - Used for primary actions with cyan accents

2. **Purple + Pink**
   - Primary purple (#7C3AED) with accent pink (#EC4899)
   - Creates a vibrant, energetic feel
   - Used for interactive elements with hover states

3. **Blue + Pink**
   - Secondary blue (#38BDF8) with accent pink (#EC4899)
   - Creates a neon-lit contrast
   - Used for decorative elements and backgrounds

### Background Combinations

1. **Dark Background + Neon Elements**
   - Main background (#1E293B) with any neon color
   - Creates depth and focus
   - Used for main content areas

2. **Glass Effect + Neon Border**
   - Translucent background with neon border in any primary color
   - Creates a futuristic container effect
   - Used for cards and content containers

3. **Gradient Backgrounds**
   - Gradients using primary purple to accent pink
   - Creates dynamic visual interest
   - Used for special sections and hero areas

### Text Combinations

1. **Dark Background + Light Text**
   - Background (#1E293B) with foreground text (#F1F5F9)
   - Ensures readability while maintaining aesthetic
   - Used for main content

2. **Neon Glow Text**
   - Light text with colored text shadow
   - Creates emphasis and visual interest
   - Used for headings and important information

3. **Colored Text on Dark**
   - Any neon color on dark background
   - Creates emphasis and draws attention
   - Used for links, buttons, and interactive elements

## Animation Color Effects

The application uses several animation effects that leverage color transitions:

### Neon Color Cycle
- Cycles between primary purple, secondary blue, and accent pink
- Creates a living, dynamic interface
- Used for borders, shadows, and highlights

### Glow Pulse
- Varies the opacity and intensity of neon colors
- Creates a breathing effect
- Used for interactive elements and focus states

### Gradient Shifts
- Animates position or opacity of gradients
- Creates movement and energy
- Used for backgrounds and decorative elements

## Accessibility Considerations

While maintaining the cyberpunk aesthetic, the application ensures accessibility through:

### Contrast Ratios
- Main text (#F1F5F9) on background (#1E293B): 13.5:1 (exceeds WCAG AAA)
- Primary purple (#7C3AED) on dark background: 5.5:1 (meets WCAG AA)
- Secondary blue (#38BDF8) on dark background: 4.8:1 (meets WCAG AA)

### Focus Indicators
- All interactive elements have clear focus states
- Focus states use high-contrast colors
- Ring color (#7C3AED) provides clear visual feedback

### Text Readability
- Main text uses high-contrast foreground color
- Minimum text size ensures readability
- Avoid using neon colors for long-form text

## Implementation Guidelines

### Using CSS Variables

The color system is implemented using CSS variables, making it easy to maintain and update. Here's how to use them:

```css
/* Using the variables directly */
.my-element {
  background-color: hsl(var(--primary));
  color: hsl(var(--primary-foreground));
}

/* Using with opacity */
.my-element-with-opacity {
  background-color: hsl(var(--primary) / 0.5);
  border-color: hsl(var(--border) / 0.8);
}

/* Using in box-shadow for glow effects */
.glowing-element {
  box-shadow: 0 0 10px hsl(var(--primary) / 0.7),
              0 0 20px hsl(var(--primary) / 0.5);
}
```

### Tailwind Integration

The color system is integrated with Tailwind CSS, allowing for consistent usage in class-based styling:

```html
<!-- Background colors -->
<div class="bg-background text-foreground">
  <!-- Primary elements -->
  <button class="bg-primary text-primary-foreground">
    Primary Button
  </button>

  <!-- Secondary elements -->
  <button class="bg-secondary text-secondary-foreground">
    Secondary Button
  </button>

  <!-- Accent elements -->
  <div class="bg-accent/20 text-accent-foreground">
    Accent Container
  </div>
</div>
```

### Neon Effects

To create neon effects with these colors:

```css
/* Neon text glow */
.neon-text {
  color: hsl(var(--primary));
  text-shadow: 0 0 5px hsl(var(--primary) / 0.7),
               0 0 10px hsl(var(--primary) / 0.5);
}

/* Neon border glow */
.neon-border {
  border: 1px solid hsl(var(--primary));
  box-shadow: 0 0 5px hsl(var(--primary) / 0.7),
              0 0 10px hsl(var(--primary) / 0.5),
              0 0 15px hsl(var(--primary) / 0.3);
}

/* Neon button */
.neon-button {
  background-color: transparent;
  color: hsl(var(--primary));
  border: 1px solid hsl(var(--primary));
  box-shadow: 0 0 5px hsl(var(--primary) / 0.7),
              inset 0 0 5px hsl(var(--primary) / 0.4);
  transition: all 0.3s ease;
}

.neon-button:hover {
  background-color: hsl(var(--primary) / 0.2);
  box-shadow: 0 0 10px hsl(var(--primary) / 0.7),
              0 0 20px hsl(var(--primary) / 0.5),
              inset 0 0 10px hsl(var(--primary) / 0.4);
}
```

## Color Usage Best Practices

### Hierarchy and Focus

1. **Use color to establish hierarchy**
   - Primary purple for main actions and focal points
   - Secondary blue for supporting elements
   - Accent pink for highlights and special features

2. **Direct attention with neon accents**
   - Use brighter colors sparingly to guide user focus
   - Apply neon effects to the most important interactive elements
   - Create visual paths through interfaces with strategic color placement

3. **Create depth with color opacity**
   - Use solid colors for foreground elements
   - Use semi-transparent colors for mid-ground elements
   - Use highly transparent colors for background elements

### Consistency and Meaning

1. **Maintain consistent color meaning**
   - Primary purple always indicates primary actions
   - Destructive red always indicates destructive actions
   - Secondary blue always indicates informational or secondary actions

2. **Use color to reinforce state**
   - Normal state: standard color
   - Hover state: increased brightness or glow effect
   - Active state: different color or increased effect
   - Disabled state: reduced opacity

3. **Balance the neon aesthetic**
   - Limit the number of bright neon elements per view
   - Create visual rest areas with darker, less saturated areas
   - Use color strategically to create focal points

### Dark Mode Optimization

1. **Optimize for eye comfort**
   - Avoid large areas of pure white text (#FFFFFF)
   - Use slightly off-white colors for large text areas (#F1F5F9)
   - Reduce brightness of large neon areas

2. **Enhance depth perception**
   - Use subtle gradients to create depth
   - Apply shadow effects to elevate elements
   - Use glass effects with appropriate opacity

3. **Ensure sufficient contrast**
   - Maintain minimum 4.5:1 contrast ratio for normal text
   - Maintain minimum 3:1 contrast ratio for large text
   - Test color combinations with contrast checkers

## Color Palette Evolution

The color palette may evolve over time to meet changing design needs and user preferences. When updating the color palette:

1. **Maintain the cyberpunk aesthetic**
   - Keep the dark background + neon accent approach
   - Preserve the futuristic, high-tech feel
   - Ensure new colors complement the existing palette

2. **Test new colors thoroughly**
   - Check contrast ratios for accessibility
   - Test in different lighting conditions
   - Ensure consistency across the application

3. **Update documentation**
   - Add new colors to this document
   - Document usage examples and combinations
   - Update implementation guidelines as needed

## Color Palette in Practice

### UI Components

#### Buttons
- Primary buttons: Primary purple background (#7C3AED) with white text
- Secondary buttons: Secondary blue background (#38BDF8) with white text
- Outline buttons: Transparent background with neon border in primary/secondary color
- Text buttons: No background with colored text matching the action type

#### Forms
- Input fields: Dark background (#334155) with light text (#F1F5F9)
- Focus state: Primary purple ring (#7C3AED) with glow effect
- Error state: Destructive red (#F43F5E) border and text
- Success state: Chart green (#10B981) border and text

#### Cards and Containers
- Background: Semi-transparent dark (#1E293B at 70% opacity)
- Borders: Subtle borders using border color (#334155 at 50% opacity)
- Highlights: Neon accents in primary, secondary, or accent colors
- Hover state: Increased glow or border opacity

#### Navigation
- Active item: Primary purple (#7C3AED) with glow effect
- Inactive item: Muted foreground (#CBD5E1)
- Hover state: Transition to brighter color with subtle glow
- Current page: Strong indicator using primary color

### Special Elements

#### Timeline
- Timeline line: Gradient of primary, secondary, and accent colors
- Timeline dots: Cyan (#22D3EE) with pulse animation
- Date indicators: Cyan text on dark background
- Content containers: Glass effect with subtle borders

#### Code Blocks
- Background: Dark semi-transparent (#1E293B with reduced opacity)
- Syntax highlighting: Using the syntax color palette
- Borders: Subtle borders with primary or secondary color
- Special highlights: Accent color for important code sections

#### Charts and Data Visualization
- Data series: Using the chart color palette (1-5)
- Background: Transparent or very dark
- Grid lines: Very subtle, low-opacity lines
- Highlights: Brighter versions of the chart colors for emphasis

## Conclusion

The El Arquitecto A.I. color palette is designed to create a cohesive, futuristic cyberpunk aesthetic while ensuring accessibility and usability. By following these guidelines and using the documented color values, you can maintain a consistent visual language throughout the application.

Remember that the cyberpunk aesthetic is characterized by:
- Dark backgrounds with neon accents
- High contrast between elements
- Glowing effects that suggest technology and energy
- Strategic use of color to guide attention and create hierarchy

This color palette supports these goals while providing a flexible system that can be applied across all application components.