$files = @("hotels\create.blade.php", "hotels\edit.blade.php", "restaurants\create.blade.php", "restaurants\edit.blade.php")
foreach($f in $files) {
  $path = "p:\xampp83\htdocs\michigan-explorer\resources\views\new_content\admin\$f"
  if (Test-Path $path) {
      $c = Get-Content $path -Raw
      
      $c = $c -replace 'id="starting_price"(.*?) />', 'id="starting_price"$1 placeholder="e.g. 199" />'
      $c = $c -replace 'id="latitude"(.*?) />', 'id="latitude"$1 placeholder="e.g. 45.8500" />'
      $c = $c -replace 'id="longitude"(.*?) />', 'id="longitude"$1 placeholder="e.g. -84.6178" />'
      
      Set-Content -Path $path -Value $c -NoNewline
  }
}
Write-Host "Done"
