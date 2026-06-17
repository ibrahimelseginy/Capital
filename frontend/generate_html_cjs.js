const fs = require('fs');

// We will just read the files and do a simple string extraction
function extractHtml(filePath) {
  let content = fs.readFileSync(filePath, 'utf-8');
  let start = content.indexOf('return `');
  if (start === -1) start = content.indexOf('return`');
  if (start !== -1) {
    let end = content.lastIndexOf('`;');
    if (end !== -1) {
      return content.substring(start + 8, end).trim();
    }
  }
  return '';
}

try {
  let homeHtml = extractHtml('./src/assets/js/pages/home.js');
  // Since homeHtml has ${} template literals, we can evaluate them by wrapping in a function
  if (homeHtml) {
    // Quick evaluate template string logic
    const evaluateTemplate = new Function('return `' + homeHtml + '`;');
    fs.writeFileSync('./src/app/pages/home/home.component.html', evaluateTemplate());
  }
  console.log('HTML extraction completed.');
} catch (e) {
  console.error('Extraction error:', e);
}
