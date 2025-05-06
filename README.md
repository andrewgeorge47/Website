# Vivolere Website

A modern, responsive website for Vivolere with a secure form submission system.

## Directory Structure

```
Website/
├── index.html          # Main website file
├── styles.css          # Stylesheet
├── thanks.html         # Thank you page
├── submit.php          # Form processing script
└── private/           # Private directory (move outside web root in production)
    ├── .htaccess      # Protects private directory
    ├── submissions.csv # Stores form submissions
    └── view_submissions.php # Admin interface to view submissions
```

## Setup Instructions

1. Move the `private` directory outside your web root directory
2. Update the path in `submit.php` to point to the new location of the private directory
3. Update the email address in `submit.php` to your actual email
4. Set secure permissions:
   ```bash
   chmod 755 private
   chmod 644 private/submissions.csv
   chmod 644 private/view_submissions.php
   chmod 644 private/.htaccess
   ```
5. Update the username and password in `private/view_submissions.php`

## Security Notes

- The `private` directory should be moved outside the web root in production
- Update the path in `submit.php` accordingly
- Change the default password in `view_submissions.php`
- Ensure proper file permissions are set
- Consider adding additional security measures like IP whitelisting

## Form Submission Process

1. User submits the form on the website
2. `submit.php` processes the submission:
   - Sanitizes the input
   - Saves to CSV file
   - Sends email notification
   - Redirects to thank you page
3. Submissions can be viewed through the secure admin interface

## Viewing Submissions

To view submissions:
1. Access `view_submissions.php` through your web browser
2. Enter the username and password
3. View submissions in a formatted table

## Customization

- Colors and styling can be modified in `styles.css`
- Form fields can be modified in `index.html`
- Email template can be customized in `submit.php`

## License

This project is open source and available under the MIT License. 