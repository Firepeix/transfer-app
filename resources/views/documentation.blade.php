<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui.css">
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-standalone-preset.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@3/swagger-ui-bundle.js" charset="UTF-8"></script>
<script>
  window.onload = function() {
    // Build a system
    window.ui = SwaggerUIBundle({
      url: window.location.origin + '/open-api/index.json',
      dom_id: '#swagger-ui',
      deepLinking: true,
      presets: [
        SwaggerUIBundle.presets.apis,
        SwaggerUIStandalonePreset
      ],
      plugins: [
        SwaggerUIBundle.plugins.DownloadUrl
      ],
      layout: "StandaloneLayout",
    })
  }
</script>
</body>
</html>
