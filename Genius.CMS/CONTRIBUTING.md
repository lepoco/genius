Get the repository to yourself
```powershell
git clone git@github.com:lepoco/Genius FOLDER_NAME
```

Go to the folder and run Composer to install the missing libraries
```powershell
cd 'FOLDER_NAME'
composer install
```

When you're done with the changes, change branch and send a commit
```powershell
git checkout -b pr/FEATURE_NAME
git add .
git commit -m 'FEATURE_NAME or whatever'
git push --set-upstream origin pr/FEATURE_NAME
```

Create new pull request from this feature