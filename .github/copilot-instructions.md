# Laravel LOA Management System

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview
This is a Laravel-based LOA (Letter of Acceptance) management system for scientific journal articles with the following key features:

### User Flow:
1. **Home Page** - Information and LOA request button
2. **LOA Request Form** - Article submission with validation
3. **LOA Search** - Search and download approved LOAs
4. **LOA Verification** - Verify LOA authenticity

### Admin Flow:
1. **Dashboard** - Statistics and overview
2. **LOA Requests Management** - Approve/reject requests
3. **Journal Management** - CRUD for journals
4. **Publisher Management** - CRUD for publishers

## Technical Stack:
- **Framework**: Laravel (PHP)
- **Database**: MySQL
- **Frontend**: Blade templates with Bootstrap/Tailwind
- **PDF Generation**: TCPDF/DomPDF for bilingual LOA certificates
- **Authentication**: Laravel built-in auth

## Database Schema:
- `loa_requests` - User LOA requests
- `loa_validated` - Approved LOAs
- `journals` - Journal master data
- `publishers` - Publisher/institution data

## Key Features:
- Auto-generated registration numbers (LOASIP.{ArticleID}.{Sequential})
- Bilingual PDF generation (Indonesian & English)
- Admin approval workflow
- LOA verification system
- Modern responsive UI

When writing code for this project, please:
- Follow Laravel best practices and conventions
- Use proper MVC architecture
- Implement proper validation and security
- Create responsive, modern UI components
- Include proper error handling and user feedback
