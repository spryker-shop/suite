#!/bin/bash

# Set unrestricted access to Postgres on Travis
sudo sed -i 's/md5/trust/g' /etc/postgresql/11/main/pg_hba.conf
sudo sed -i 's/peer/trust/g' /etc/postgresql/11/main/pg_hba.conf

# Restart Postgres service
sudo pg_ctlcluster --skip-systemctl-redirect 11 main restart
