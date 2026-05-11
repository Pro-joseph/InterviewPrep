# AI Question Generation Specification

## Overview
Generate interview questions using Groq API based on concept title and explanation

## Features

### 1. Generate Questions
- Button on concept detail page: "Générer des questions"
- API call to Groq with concept details
- Generate 5 questions per request
- Store questions in database
- Display on concept detail page

### 2. View Question History
- All generations shown on concept detail
- Each generation shows:
  - Date/time of generation
  - List of 5 questions
- Sorted by newest first

### 3. Delete Generation
- Delete button on each generation
- Permanent deletion (no archive)

## API Integration

### Groq Configuration
- API Key: stored in `.env` as `GROQ_API_KEY`
- Model: `llama-3.1-8b-instant`
- Endpoint: `https://api.groq.com/openai/v1/chat/completions`

### Request Format
```json
{
  "model": "llama-3.1-8b-instant",
  "messages": [
    {
      "role": "system",
      "content": "Tu es un expert en recrutement technique..."
    },
    {
      "role": "user",
      "content": "Génère 5 questions..."
    }
  ],
  "temperature": 0.7,
  "max_tokens": 1000
}
```

### Response Format
Expected JSON array:
```json
[
  {"question": "Question 1 text"},
  {"question": "Question 2 text"},
  {"question": "Question 3 text"},
  {"question": "Question 4 text"},
  {"question": "Question 5 text"}
]
```

## Database Schema
```
generated_questions
  - id (bigint, PK)
  - concept_id (bigint, FK)
  - user_id (bigint, FK)
  - questions (json array)
  - timestamps

concepts table must have user_id for ownership
```

## Error Handling
- No API key: display error message
- API failure: display error, log details
- Empty response: show "Aucune question générée"
- Timeout: 60 second timeout

## Routes
| Method | URI | Controller |
|--------|-----|------------|
| POST | /concepts/{concept}/generate | generate |
| DELETE | /questions/{question} | destroy |

## Acceptance Criteria
- [ ] User can generate 5 questions for a concept
- [ ] Questions are stored in database
- [ ] User can view all generated questions
- [ ] User can delete a generation
- [ ] Errors are handled gracefully
- [ ] Loading state shown during generation