// Script to replace API URL in environment.prod.ts at build time
const fs = require('fs');
const path = require('path');

const envFile = path.join(__dirname, 'src', 'environments', 'environment.prod.ts');
const apiUrl = process.env.API_URL || 'https://your-backend-url.onrender.com/api';

// Read the file
let content = fs.readFileSync(envFile, 'utf8');

// Replace the apiUrl with the environment variable
content = content.replace(
  /apiUrl:\s*['"](.*?)['"]/,
  `apiUrl: '${apiUrl}'`
);

// Write back
fs.writeFileSync(envFile, content, 'utf8');

console.log(`✅ Replaced API URL with: ${apiUrl}`);

