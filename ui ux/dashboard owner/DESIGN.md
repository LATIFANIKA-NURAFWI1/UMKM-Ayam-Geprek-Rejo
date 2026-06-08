---
name: Modern Culinary KDS
colors:
  surface: '#f7f9ff'
  surface-dim: '#d7dadf'
  surface-bright: '#f7f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f4f9'
  surface-container: '#ebeef3'
  surface-container-high: '#e5e8ee'
  surface-container-highest: '#e0e3e8'
  on-surface: '#181c20'
  on-surface-variant: '#5e3f3b'
  inverse-surface: '#2d3135'
  inverse-on-surface: '#eef1f6'
  outline: '#936e69'
  outline-variant: '#e8bcb6'
  surface-tint: '#c0000b'
  primary: '#bc000a'
  on-primary: '#ffffff'
  primary-container: '#e61919'
  on-primary-container: '#fffbff'
  inverse-primary: '#ffb4aa'
  secondary: '#785900'
  on-secondary: '#ffffff'
  secondary-container: '#fdc003'
  on-secondary-container: '#6c5000'
  tertiary: '#5a5c5e'
  on-tertiary: '#ffffff'
  tertiary-container: '#737576'
  on-tertiary-container: '#fcfdfe'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdad5'
  primary-fixed-dim: '#ffb4aa'
  on-primary-fixed: '#410001'
  on-primary-fixed-variant: '#930006'
  secondary-fixed: '#ffdf9e'
  secondary-fixed-dim: '#fabd00'
  on-secondary-fixed: '#261a00'
  on-secondary-fixed-variant: '#5b4300'
  tertiary-fixed: '#e1e3e4'
  tertiary-fixed-dim: '#c5c7c8'
  on-tertiary-fixed: '#191c1d'
  on-tertiary-fixed-variant: '#454748'
  background: '#f7f9ff'
  on-background: '#181c20'
  surface-variant: '#e0e3e8'
typography:
  order-number:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '800'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '500'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-caps:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-padding: 24px
  card-gap: 16px
  section-margin: 32px
---

## Brand & Style

This design system reimagines the kitchen display and cashier dashboard with a **Clean Modern** aesthetic that balances high-intensity operational needs with a welcoming, professional atmosphere. By replacing dated gradients and harsh contrasts with a refined palette of Strong Red and Gold against a sterile White backdrop, the UI shifts from "utility software" to a "modern culinary tool."

The emotional response is one of **Efficiency and Warmth**. It uses ample whitespace to reduce cognitive load during peak kitchen hours while employing large rounded corners to feel approachable. The style leans into a subtle "Soft UI" approach—utilizing gentle depth rather than flat blocks of color to help kitchen staff differentiate between interactive zones and information displays.

## Colors

The color strategy is focused on high-speed legibility and functional hierarchy:

- **Primary (Strong Red):** Used strictly for critical calls to action, urgent order statuses, and branding accents. It provides the "heat" necessary for a fast-paced food environment.
- **Secondary (Gold/Yellow):** Reserved for highlights, loyalty points, premium status, and "Ready" indicators. It provides a warm contrast to the red without the vibration of the previous orange.
- **Background (Clean White):** A vast, neutral canvas that ensures the colorful food service elements remain the primary focus.
- **Functional Grays:** A scale of cool grays is used for secondary text and thin card borders to maintain a professional, tech-forward look.

## Typography

The system utilizes **Plus Jakarta Sans** for headlines and brand-heavy elements to provide a friendly, modern character. For high-density data like order lists and technical labels, **Inter** is used for its superior legibility at small sizes and its neutral, systematic feel.

Order numbers are given the highest hierarchy with an ultra-bold weight and tight letter spacing to ensure they are visible from across a busy kitchen. Label styles are often capitalized with increased tracking to create a clear "form-label" distinction for metadata like "Metode Bayar" or "Waktu Order."

## Layout & Spacing

The layout follows a **Fluid Grid** model designed for landscape tablet and industrial monitor orientations. It prioritizes vertical scanning of order columns.

- **Dashboard:** A 2-column or 3-column split with clear headers. Columns are separated by 32px to prevent visual bleeding.
- **Rhythm:** An 8px base grid ensures consistent alignment. Internal card padding is set to 20px (2.5 units) to give order items "room to breathe," preventing the claustrophobic feel of data-heavy interfaces.
- **Mobile/Handheld:** When used on smaller devices, columns stack vertically, and header actions (like "Keluar") move to a bottom-fixed navigation or a simplified top-right profile icon.

## Elevation & Depth

Visual hierarchy is achieved through a combination of **Tonal Layers** and **Ambient Shadows**:

- **Surface Level 0:** The main background (Clean White).
- **Surface Level 1 (Cards):** Order cards use a very thin 1px border (#E9ECEF) and a soft, wide-spread shadow (10% opacity) to appear slightly lifted.
- **Surface Level 2 (Modals):** High-priority overlays (Detail Pesanan) use a more aggressive blur and a backdrop overlay (Scrim) at 40% black to focus the user’s attention.
- **Interactive Elements:** Buttons utilize a slight "press" effect, moving from a medium shadow to no shadow on interaction to simulate tactile feedback.

## Shapes

The shape language is defined by **large radii** to soften the industrial nature of kitchen software.
- **Standard Cards:** 1rem (16px) corner radius.
- **Pills/Buttons:** Fully rounded (capsule) style for status indicators and primary actions.
- **Inputs:** 0.75rem (12px) to maintain a consistent language with the cards.
- **Illustrations:** Minimalist, "friendly-tech" line art with rounded terminal ends, avoiding sharp points.

## Components

### Pills & Status
Use high-contrast capsules. "Pending" uses a Gold background with Dark text, while "Proses" or "Lunas" uses the Primary Red or a secondary success green. Pills should have a horizontal padding of 12px and use the `label-caps` typography.

### Cards
Cards are the primary container. They feature a 1px border in a light gray (#E9ECEF). The header of the card should use the `order-number` style to make the ID the hero of the element.

### Buttons
Primary buttons are solid Red with White text. Secondary buttons (e.g., "Detail", "Batal") use an "Outlined" style with thin borders to keep the UI from becoming too heavy with color.

### Input Fields
Soft-rounded corners with a subtle light-gray background (#F1F3F5) and no border unless focused. When focused, they use a 2px Gold or Red stroke to indicate activity.

### Illustrations
Use professional, minimalist "empty state" illustrations. These should be monochromatic (using the functional grays) with small pops of Gold or Red to tie into the brand without distracting from active tasks.