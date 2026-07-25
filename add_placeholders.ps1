$files = @("hotels\create.blade.php", "hotels\edit.blade.php", "restaurants\create.blade.php", "restaurants\edit.blade.php")
foreach($f in $files) {
  $path = "p:\xampp83\htdocs\michigan-explorer\resources\views\new_content\admin\$f"
  if (Test-Path $path) {
      $c = Get-Content $path -Raw
      
      # Remove any existing placeholders just in case to avoid duplicates
      $c = [regex]::Replace($c, ' placeholder="e\.g\.[^"]+"', "")
      
      $c = $c -replace 'id="name"(.*?) />', 'id="name"$1 placeholder="e.g. The Grand Hotel" />'
      $c = $c -replace 'id="slug"(.*?) />', 'id="slug"$1 placeholder="e.g. the-grand-hotel" />'
      $c = $c -replace 'id="zip"(.*?) />', 'id="zip"$1 placeholder="e.g. 49757" />'
      $c = $c -replace 'id="address"(.*?) />', 'id="address"$1 placeholder="e.g. 286 Grand Avenue" />'
      $c = $c -replace 'id="phone"(.*?) />', 'id="phone"$1 placeholder="e.g. +1 555-123-4567" />'
      $c = $c -replace 'id="email"(.*?) />', 'id="email"$1 placeholder="e.g. info@example.com" />'
      $c = $c -replace 'id="website"(.*?) />', 'id="website"$1 placeholder="e.g. https://www.example.com" />'
      $c = $c -replace 'id="affiliate_url"(.*?) />', 'id="affiliate_url"$1 placeholder="e.g. https://booking.com/..." />'
      
      Set-Content -Path $path -Value $c -NoNewline
  }
}
Write-Host "Done"
