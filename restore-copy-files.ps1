# Restore copy files to originals
$copyFiles = Get-ChildItem -Path "admin/include/*copy.php" -File

foreach ($file in $copyFiles) {
    $originalName = $file.Name -replace " copy", ""
    $originalPath = Join-Path -Path $file.DirectoryName -ChildPath $originalName
    
    # Make backup of current file
    $backupName = $originalName -replace ".php", "_backup_$(Get-Date -Format 'yyyyMMdd_HHmmss').php"
    $backupPath = Join-Path -Path $file.DirectoryName -ChildPath $backupName
    
    Write-Host "Processing: $($file.Name) -> $originalName"
    Write-Host "Creating backup: $backupName"
    
    # Create backup
    Copy-Item -Path $originalPath -Destination $backupPath -Force
    
    # Copy the 'copy' file to the original
    Copy-Item -Path $file.FullName -Destination $originalPath -Force
    
    Write-Host "Restored: $($file.Name) to $originalName" -ForegroundColor Green
    Write-Host "-----------------------------------"
}

Write-Host "All files have been restored!" -ForegroundColor Green 