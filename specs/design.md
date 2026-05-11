# InterviewPrep - Design Specification

## Aesthetic Direction
**Theme**: Editorial Moroccan Sunset - Sophisticated dark theme with warm amber/gold accents inspired by Moroccan craftsmanship and the warm tones of a Casablanca sunset.

**Tone**: Professional, refined, warm - balancing technical seriousness with approachability.

---

## Design System

### Color Palette
```css
:root {
    /* Primary - Warm Amber */
    --primary-50: #FEF7E8;
    --primary-100: #FDEECF;
    --primary-200: #FBDE9F;
    --primary-300: #F8C76B;
    --primary-400: #F5B243;
    --primary-500: #F29B1F;
    --primary-600: #D4820F;
    --primary-700: #B46808;
    --primary-800: #8F5207;
    --primary-900: #6B3D06;

    /* Background - Deep Charcoal */
    --bg-primary: #0F0F0F;
    --bg-secondary: #1A1A1A;
    --bg-tertiary: #252525;
    --bg-card: #1E1E1E;
    --bg-elevated: #2A2A2A;

    /* Text */
    --text-primary: #FAFAFA;
    --text-secondary: #A3A3A3;
    --text-muted: #6B6B6B;

    /* Accent Colors for Domains */
    --domain-laravel: #FF5722;
    --domain-php: #777BB4;
    --domain-mysql: #4479A1;
    --domain-api: #00BCD4;
    --domain-oop: #9C27B0;
    --domain-git: #F05032;
    --domain-docker: #2496ED;

    /* Status Colors */
    --status-review: #EF4444;
    --status-progress: #F59E0B;
    --status-mastered: #22C55E;

    /* Difficulty Colors */
    --difficulty-junior: #22C55E;
    --difficulty-mid: #F59E0B;
    --difficulty-senior: #EF4444;
}
```

### Typography
- **Display Font**: "Playfair Display" (serif) - for headings, hero text
- **Body Font**: "DM Sans" - for all body text, clean and readable
- **Mono Font**: "JetBrains Mono" - for code snippets

### Spacing System
- Base unit: 4px
- Scale: 4, 8, 12, 16, 24, 32, 48, 64, 96px

---

## Page Designs

### 1. Welcome Page (landing)
- Split layout: left side welcome message, right side login/register
- Animated gradient background (amber to deep charcoal)
- Logo: "InterviewPrep" with subtle icon
- Clean form design for authentication

### 2. Login Page
- Centered card on dark background
- Email and password fields
- "Remember me" checkbox
- Link to register
- Forgot password link

### 3. Register Page
- Centered card
- Name, email, password, confirm password
- Terms acceptance checkbox

### 4. Dashboard (Home)
- Welcome message with user name
- Stats cards grid:
  - Total concepts
  - Mastered concepts
  - In progress
  - To review
- Progress by domain (horizontal bars)
- Best performing domain
- Most needs attention domain
- Recent activity (last added concepts)
- Quick actions: Add domain, Add concept

### 5. Domains List Page
- Header with "Mes Domaines" title + Add button
- Grid of domain cards (3 columns desktop):
  - Domain name (large)
  - Color badge
  - Progress bar (mastered/total)
  - Stats: X/Y maîtrisés
  - Actions: Edit, Delete
- Empty state with call-to-action

### 6. Domain Create/Edit Modal
- Modal overlay
- Name input
- Color picker (preset colors)
- Save/Cancel buttons

### 7. Concepts List Page (within a domain)
- Breadcrumb: Dashboard > Domains > [Domain Name]
- Header: Domain name + Add concept button
- Filters bar:
  - Status filter (All, À revoir, En cours, Maîtrisé)
  - Difficulty filter (All, Junior, Mid, Senior)
- Grid of concept cards (2 columns):
  - Title
  - Difficulty badge (color-coded)
  - Status badge
  - Quick status dropdown
  - Actions: View, Edit, Delete
- Pagination

### 8. Concept Detail Page
- Breadcrumb navigation
- Header section:
  - Title
  - Difficulty badge
  - Status badge
  - Edit button
- Content section:
  - Explanation (rich text)
- Questions section:
  - "Générer des questions" button
  - Generated questions list (accordion style)
  - Each generation shows date + 5 questions
  - Delete generation option

### 9. Concept Create/Edit Page
- Full-page form
- Title input
- Domain selector (dropdown)
- Explanation textarea (with markdown support)
- Difficulty select (Junior/Mid/Senior)
- Status select (À revoir/En cours/Maîtrisé)
- Save/Cancel buttons

### 10. Archived Concepts Page
- Similar to concepts list but shows archived
- "Archivés" tab in navigation or separate page
- Each archived concept shows:
  - Title
  - Original domain
  - Date archived
  - Restore button
  - Permanent delete button

---

## Component Library

### Buttons
- Primary: Amber background, dark text
- Secondary: Transparent with amber border
- Danger: Red background
- Ghost: No background, text only
- Icon buttons: Circular, subtle hover

### Cards
- Dark background (#1E1E1E)
- Subtle border (1px #333)
- Rounded corners (12px)
- Hover: subtle lift effect
- Shadow: soft drop shadow

### Badges
- Pill shape (rounded-full)
- Color-coded by type
- Small text (12px)

### Forms
- Dark inputs (#252525)
- Amber focus ring
- Clear labels above inputs
- Error messages below inputs
- Placeholder text in muted color

### Modals
- Centered, max-width 500px
- Dark background with blur overlay
- Smooth fade-in animation

### Progress Bars
- Rounded, 8px height
- Animated fill
- Percentage label

### Dropdowns
- Custom styled select
- Dark theme matching design
- Smooth transition on open

---

## Animations & Interactions

### Page Load
- Staggered fade-in for cards (50ms delay each)
- Progress bars animate from 0 to value

### Hover Effects
- Cards: subtle lift (translateY -2px)
- Buttons: brightness increase
- Links: color transition

### Status Change
- Quick dropdown updates with brief highlight
- Optimistic UI update

### AI Generation
- Loading spinner with pulsing effect
- Questions appear with slide-in animation

---

## Responsive Breakpoints
- Mobile: < 640px (single column)
- Tablet: 640px - 1024px (2 columns)
- Desktop: > 1024px (3-4 columns)