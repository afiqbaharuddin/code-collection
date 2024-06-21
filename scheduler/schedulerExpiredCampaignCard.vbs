Dim objShell,command
command = "powershell.exe -NoLogo -WindowStyle Hidden -ExecutionPolicy unrestricted C:\xampp\htdocs\work-office\parkson-evoucher-admin\scheduler\schedulerExpiredCampaignCard.ps1"
Set objShell = CreateObject("WScript.Shell")
objShell.Run command,0

