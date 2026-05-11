# Dashboard Specification

## Overview
Main landing page showing user's overall progress and quick access to domains

## Stats Display

### 1. Overview Stats (4 cards)
- Total Concepts: count of all user concepts
- Maîtrisés: count of concepts with status = maîtrisé
- En cours: count of concepts with status = en cours
- À revoir: count of concepts with status = à revoir

### 2. Domain Progress Section
- List all domains with progress bars
- Show: domain name (with color dot), mastered/total count
- Progress bar: (mastered / total) * 100%
- Clickable to navigate to domain

### 3. Quick Stats Sidebar
- Best performing domain (highest mastery %)
- Domain needing attention (lowest mastery %)

### 4. Quick Actions
- "Nouveau Domaine" button
- Link to domains list

## Data Calculations

### From Concepts Table
```
totalConcepts = COUNT where user_id = auth()->id()
mastered = COUNT where status = 'maitrise' AND user_id = auth()->id()
inProgress = COUNT where status = 'en_cours' AND user_id = auth()->id()
toReview = COUNT where status = 'a_revoir' AND user_id = auth()->id()
```

### Per Domain
```
domain.concepts_count = COUNT where domain_id = domain.id
domain.mastered_count = COUNT where domain_id = domain.id AND status = 'maitrise'
progress_percentage = (mastered_count / concepts_count) * 100
```

## UI Layout

```
┌─────────────────────────────────────────────────────────┐
│  Dashboard Header                                       │
│  "Bienvenue {name}, voici ta progression"              │
│  [Nouveau Domaine Button]                               │
├─────────────────────────────────────────────────────────┤
│  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐            │
│  │ Total  │ │Maîtrisés│ │En cours│ │À revoir│            │
│  │   42   │ │   18   │ │   14   │ │   10   │            │
│  └────────┘ └────────┘ └────────┘ └────────┘            │
├─────────────────────────────────────────────────────────┤
│  Progression par Domaine    │  Aperçu Rapide            │
│  ┌────────────────────────┐ │ ┌──────────────────────┐  │
│  │ ● Laravel ORM 8/12 ████│ │ │ Meilleur: PHP OOP   │  │
│  │ ● PHP OOP    6/10 ████ │ │ │ À améliorer: API    │  │
│  │ ● MySQL      4/8  ███  │ │ └──────────────────────┘  │
│  └────────────────────────┘ │                            │
├─────────────────────────────────────────────────────────┤
│  Domaines Grid                                         │
│  [Domain Card] [Domain Card] [Domain Card] ...         │
└─────────────────────────────────────────────────────────┘
```

## Empty State
When no domains exist:
- Show illustration/icon
- "Aucun domaine" message
- "Commence par créer ton premier domaine technique"
- "Créer un domaine" button

## Acceptance Criteria
- [ ] Show correct total concept count
- [ ] Show correct mastered/in progress/to review counts
- [ ] Show all domains with progress bars
- [ ] Show best and worst performing domains
- [ ] Empty state when no domains
- [ ] Quick navigation to create domain
- [ ] Responsive design (mobile/tablet/desktop)