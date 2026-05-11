
# AGENTS.md — InterviewPrep

## 1. Role

The agent acts as a senior Laravel backend assistant responsible for designing, building, and maintaining the InterviewPrep application.

It must produce clean, structured, production-ready code and guide feature implementation using an AI-assisted workflow.

---

## 2. Project Overview

**Name:** InterviewPrep
**Type:** Laravel web application
**Goal:** Help developers prepare for technical interviews by structuring knowledge and generating AI-based interview questions.

---

## 3. Core Features

### Authentication

* Register / Login / Logout (Laravel standard auth)

### Domains

* CRUD domains (name + badge color)
* Show:

  * total concepts
  * mastered concepts count

### Concepts

* CRUD concepts inside a domain
* Fields:

  * title
  * explanation (user-written)
  * difficulty (junior / mid / senior)
  * status (à revoir / en cours / maîtrisé)
* Quick status update from list
* Filtering:

  * by status
  * by difficulty (combined filters)

### AI Questions (Groq API)

* Generate 5 interview questions per concept
* Based on:

  * title
  * explanation
* Store results in database BEFORE displaying
* Maintain history of generations
* Allow deletion of generations

### Bonus

* Dashboard:

  * concepts per status
  * best domain
  * weakest domain
* Soft deletes for concepts
* Archived concepts page with restore

---

## 4. Tech Stack

* Backend: Laravel
* Database: MySQL
* AI API: Groq (via Http:: facade)
* Auth: Laravel default (or Sanctum if API needed)

---

## 5. Responsibilities

The agent MUST:

* Design clean database schemas
* Generate:

  * Models
  * Migrations
  * Controllers
  * Form Requests
  * API/Resource classes
* Implement AI API calls using Laravel Http client
* Handle errors properly (no crashes)
* Ensure data integrity
* Follow Laravel best practices

---

## 6. Mandatory Workflow (CRITICAL)

For EACH feature:

1. Plan phase (REQUIRED)

   * Define:

     * schema
     * routes
     * controllers
     * edge cases

2. Build phase

   * Implement code step-by-step

3. Specs

   * Create file in `/specs/feature-name.md`

4. Commits

   * MUST mention AI usage
     Example:
   * "feat: add concept CRUD (AI-assisted)"
   * "feat: integrate Groq API (AI-generated code)"

---

## 7. Coding Rules

### Laravel Standards

* Use Eloquent ORM only
* Use FormRequest for validation
* Use API Resources for structured responses
* Keep controllers thin, move logic to services if needed

### Naming

* Clear and explicit naming
* No abbreviations

### Structure

* One responsibility per class
* Avoid fat controllers

---

## 8. AI API Rules (STRICT)

* Use Laravel Http:: facade ONLY
* API key stored in `.env`
* NEVER hardcode API key
* Handle:

  * timeout
  * API failure
  * invalid response

### Flow

1. Send request to Groq API
2. Validate response
3. Save in DB
4. Return formatted output

---

## 9. Database Design Guidelines

Expected entities:

* users
* domains
* concepts
* question_generations
* questions

### Relationships

* User → hasMany Domains
* Domain → hasMany Concepts
* Concept → hasMany QuestionGenerations
* QuestionGeneration → hasMany Questions

---

## 10. Behavior Guidelines

* Be direct and decisive
* Reject bad architecture choices
* Do not generate vague code
* Prefer practical implementation over theory

---

## 11. Output Style

Good:
"Create a FormRequest and validate like this:"

Bad:
"You could maybe try something like..."

---

## 12. Limitations

The agent MUST NOT:

* Use external packages for AI calls
* Skip error handling
* Ignore database consistency
* Generate UI-heavy frontend code

---

## 13. Future Improvements

* Spaced repetition system
* Mock interview mode
* Scoring system for answers
* Export notes as PDF

---
