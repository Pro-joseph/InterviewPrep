# Concept Management Specification

## Overview
Manage technical concepts within domains

## Features

### 1. List Concepts
- Display all concepts in a domain
- Show: title, difficulty badge, status badge
- Filters: by status, by difficulty
- Quick status update dropdown
- Pagination (12 per page)

### 2. Create Concept
- Form with:
  - Title (required)
  - Explanation (required, textarea)
  - Difficulty: junior | mid | senior (required)
  - Status: à revoir | en cours | maîtrisé (default: à revoir)
- Validation: all fields required

### 3. View Concept Detail
- Full explanation display
- Difficulty and status badges
- AI questions section (future feature)

### 4. Edit Concept
- Pre-filled form
- Update all fields

### 5. Quick Status Update
- Dropdown in concept list
- Immediate update without page reload
- Status options: à revoir, en cours, maîtrisé

### 6. Delete Concept
- Soft delete (archived)
- Restore from archived page

## Database Schema
```
concepts
  - id (bigint, PK)
  - domain_id (bigint, FK)
  - user_id (bigint, FK)
  - title (string)
  - explanation (text)
  - difficulty (enum: junior, mid, senior)
  - status (enum: a_revoir, en_cours, maitrise)
  - deleted_at (timestamp, nullable)
  - timestamps
```

## Status Mapping (for display)
| DB Value | Display |
|----------|---------|
| a_revoir | À revoir |
| en_cours | En cours |
| maitrise | Maîtrisé |

## Difficulty Mapping
| DB Value | Display |
|----------|---------|
| junior | Junior |
| mid | Mid |
| senior | Senior |

## Routes
| Method | URI | Controller |
|--------|-----|------------|
| GET | /domains/{domain}/concepts | index |
| POST | /domains/{domain}/concepts | store |
| GET | /domains/{domain}/concepts/create | create |
| GET | /domains/{domain}/concepts/{concept} | show |
| GET | /domains/{domain}/concepts/{concept}/edit | edit |
| PUT | /domains/{domain}/concepts/{concept} | update |
| DELETE | /domains/{domain}/concepts/{concept} | destroy |
| PATCH | /concepts/{concept}/status | updateStatus |

## Acceptance Criteria
- [ ] User can view concepts in a domain
- [ ] User can create a new concept
- [ ] User can edit a concept
- [ ] User can delete a concept (soft delete)
- [ ] User can filter by status and difficulty
- [ ] User can quickly update status from list
- [ ] Archived concepts can be restored