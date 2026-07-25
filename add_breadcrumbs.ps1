$files = Get-ChildItem -Path "p:\xampp83\htdocs\michigan-explorer\resources\views\new_content\admin\*" -Include "create.blade.php", "edit.blade.php" -Recurse

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    if ($content -match "<nav aria-label=`"breadcrumb`"") { continue }
    
    # Extract Title
    if ($content -match "@section\('title',\s*'([^']+)'\)") {
        $title = $matches[1]
    } else {
        $title = "Dashboard"
    }

    # Extract parent from directory name
    $dirName = $file.Directory.Name
    # convert "restaurant_categories" to "Restaurant Categories"
    $parentName = (Get-Culture).TextInfo.ToTitleCase($dirName.Replace('_', ' '))
    # convert "restaurant_categories" to "restaurant-categories"
    $routeName = $dirName.Replace('_', '-') + ".index"
    
    $breadcrumbHtml = "<nav aria-label=`"breadcrumb`" class=`"mb-4`">`n" +
                      "  <ol class=`"breadcrumb`">`n" +
                      "    <li class=`"breadcrumb-item`"><a href=`"{{ url('/dashboard') }}`">Dashboard</a></li>`n" +
                      "    <li class=`"breadcrumb-item`"><a href=`"{{ route('$routeName') }}`">$parentName</a></li>`n" +
                      "    <li class=`"breadcrumb-item active`" aria-current=`"page`">$title</li>`n" +
                      "  </ol>`n" +
                      "</nav>`n`n<div class=`"card mb-4`">"
    
    # Replace the FIRST occurrence of <div class="card mb-4">
    $regex = [regex]::new('<div class="card mb-4">')
    $content = $regex.Replace($content, $breadcrumbHtml, 1)
    
    Set-Content -Path $file.FullName -Value $content -NoNewline
}

Write-Host "Done"
