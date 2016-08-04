This cronjob will run weekly. 
It'll clone the visualisation repository and update the google spredasheet.
Make sure the data directory to save the google spreadsheet is created beforehand, otherwise it'll not save the files in the directory.
To run this cronjob change the crontab manually and copy the command with the right path to the cron.sh file..

Command to do this is:

#remove earlier cronjob from crontab
crontab -r

#Add new job to crontab
crontab -e

#copy the command from the dashbpard.cron file with correct file path.
0 4 * * 1 /bin/bash path-to-the-dashboard-repository-cron.sh
