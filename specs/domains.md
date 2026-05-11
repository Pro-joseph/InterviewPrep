# Domain Management Specification

## Overview
Manage technical domains (e.g., Laravel ORM, PHP OOP, MySQL)

## Features

### 1. List Domains
- Display all user domains in a grid
- Show: domain name, color badge, concept count, mastered count
- Progress bar showing mastery percentage

### 2. Create Domain
- Modal form with:
  - Name input (required, max 255 chars)
  - Color picker (8 preset colors)
- Validation: name required, color required
- Success: redirect to domains list with success message

### 3. Edit Domain
- Same form as create
- Pre-filled with existing values
- Update via PUT request

### 4. Delete Domain
- Confirmation before delete
- Soft delete (archived)
- Cascade delete all related concepts

## Database Schema
```
domains
  - id (bigint, PK)
  - user_id (bigint, FK)
  - name (string)
  - color (string, 7 chars)
  - deleted_at (timestamp, nullable)
  - timestamps
```

## Routes
| Method | URI | Controller |
|--------|-----|------------|
| GET | /domains | index |
| POST | /domains | store |
| GET | /domains/create | create |
| GET | /domains/{domain}/edit | edit |
| PUT | /domains/{domain} | update |
| DELETE | /domains/{domain} | destroy |

## Acceptance Criteria
- [ ] User can view all their domains
- [ ] User can create a new domain with name and color
- [ ] User can edit existing domain
- [ ] User can delete a domain (soft delete)
- [ ] Progress is calculated correctly (mastered/total)
- [ ] Empty state shown when no domains