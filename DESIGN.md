---
name: Technical Synthesis
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#45464d'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#565e74'
  primary: '#000000'
  on-primary: '#ffffff'
  primary-container: '#131b2e'
  on-primary-container: '#7c839b'
  inverse-primary: '#bec6e0'
  secondary: '#0058be'
  on-secondary: '#ffffff'
  secondary-container: '#2170e4'
  on-secondary-container: '#fefcff'
  tertiary: '#000000'
  on-tertiary: '#ffffff'
  tertiary-container: '#191c1e'
  on-tertiary-container: '#818486'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dae2fd'
  primary-fixed-dim: '#bec6e0'
  on-primary-fixed: '#131b2e'
  on-primary-fixed-variant: '#3f465c'
  secondary-fixed: '#d8e2ff'
  secondary-fixed-dim: '#adc6ff'
  on-secondary-fixed: '#001a42'
  on-secondary-fixed-variant: '#004395'
  tertiary-fixed: '#e0e3e5'
  tertiary-fixed-dim: '#c4c7c9'
  on-tertiary-fixed: '#191c1e'
  on-tertiary-fixed-variant: '#444749'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  title-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-code:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-caps:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 12px
    letterSpacing: 0.08em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-max: 1440px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 40px
  stack-sm: 4px
  stack-md: 12px
  stack-lg: 24px
---

## Brand & Style

The design system is engineered for **BridgeOps AI**, a platform that translates technical velocity into business intelligence. The brand personality is authoritative yet accessible, acting as a sophisticated "translator" between engineering depth and executive clarity. 

The visual style is **Corporate Modern** with a focus on **High-Contrast Minimalism**. It prioritizes extreme legibility and structured information density. The emotional goal is to evoke a sense of controlled power and reliability. By utilizing generous whitespace and a rigorous grid, the UI mirrors the structural integrity of a bridge, connecting disparate data points into a unified, actionable narrative.

## Colors

The palette is anchored by **Technical Navy** (#0F172A), representing the stability of infrastructure and the depth of engineering data. A vibrant **Action Blue** (#3B82F6) is used sparingly for primary interactions and focus states.

The background strategy utilizes a tiered "White-on-Gray" approach. The primary canvas is pure white, while **Subtle Slate** (#F8FAFC) is used for secondary containers and sidebars to create a clear spatial hierarchy without the need for heavy lines. Semantic colors for Success, Warning, and Danger are highly saturated to ensure that risk assessments are immediately scannable within dense dashboards.

## Typography

This design system employs **Inter** for all primary interface elements, chosen for its exceptional legibility in data-heavy environments and its neutral, professional tone. To reinforce the engineering connection, **JetBrains Mono** is used for secondary labels, metadata, and "bridge" elements where technical precision is highlighted.

Typography follows a strict hierarchy: Large displays use tight letter-spacing and bold weights to command attention, while body text maintains a comfortable line height for long-form reporting. All labels for technical IDs or status indicators should use the Monospaced font to differentiate data from narrative text.

## Layout & Spacing

The layout utilizes a **12-column fluid grid** for desktop, transitioning to a **4-column grid** for mobile. Spacing is governed by an 8px linear scale to ensure mathematical harmony across all components.

Information is grouped into "Logical Clusters" using generous margins (40px) to prevent cognitive overload. On mobile, padding is reduced to 16px to maximize data density while maintaining touch-target integrity. The "Bridge" concept is reinforced through horizontal alignment: technical metrics are left-aligned, while business outcomes are right-aligned or centered within cards to create a visual flow across the horizontal axis.

## Elevation & Depth

Hierarchy is established using **Tonal Layers** and **Ambient Shadows**. Instead of heavy borders, the design system uses surface color shifts (White to Slate-50) to distinguish between the background and interactive panels.

Shadows are "Ultra-Soft": high blur radius (20px-40px), very low opacity (4-6%), and slightly tinted with the Primary Navy color to ensure they feel integrated rather than "floating." Elements at higher elevations (like modals) receive a 1px subtle outline in a light gray to maintain crispness against white backgrounds.

## Shapes

The shape language is defined by **High-Radius Geometry**. Standard components use a 0.5rem base, but top-level containers and cards utilize `rounded-2xl` (1rem) or higher to soften the enterprise aesthetic and make the platform feel more approachable. 

The "Bridge" metaphor is subtly echoed through the use of pill-shaped buttons and tags, which contrast against the rigid structure of the grid. Any "Technical" elements, like code blocks or data inputs, may use a slightly tighter radius (0.25rem) to signify precision and structure.

## Components

### Buttons & Actions
Primary buttons are solid Technical Navy with high-contrast white text. Secondary buttons use a ghost style with a subtle 1px border. Hover states should feature a slight lift (elevation change) rather than a drastic color shift.

### Cards & Reporting
Cards are the primary vehicle for data. They must feature `rounded-2xl` corners and a soft ambient shadow. Every card should have a clear "Header" section using the `label-caps` style to categorize the source (e.g., "ENGINEERING DATA" vs "BUSINESS IMPACT").

### Status Indicators (Chips)
Status chips use a "Duo-tone" approach: a low-opacity background of the semantic color (Green, Orange, Red) with a high-opacity text and a small leading icon for accessibility.

### Inputs & Fields
Input fields are minimal, using a light gray background and a 2px Action Blue bottom border or focus ring to indicate activity. Labels are always positioned above the input in `body-sm` bold.

### Bridge Metrics
A custom component unique to this design system: A split-view list item where a technical commit message (left) is linked by a subtle dashed line to a business requirement (right), visually "bridging" the two contexts.