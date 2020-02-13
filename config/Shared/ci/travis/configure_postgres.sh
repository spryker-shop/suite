#!/bin/bash

# Set poor access to the Postgres for Travis testing needs
sed -ie 's/md5/trust/g' /etc/postgresql/12/main/pg_hba.conf
sed -ie 's/peers/trust/g' /etc/postgresql/12/main/pg_hba.conf

# Restart Postgres service
/etc/init.d/postgresql restart 12
