#!/bin/bash

CERTS_DIR=/certs

# Install openssl if it not installed
if [ "$(which openssl)" == "" ]; then
  apk add openssl
fi

if [ ! -f ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.key -a ! -f ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.crt ]; then
  # Generate bundle
  ${ifVerbose} && echo -e "${INFO}Generating CA cert and private key${NC}"
  openssl req -nodes -newkey rsa:2048 -out ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.csr -keyout ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.key -subj "/C=DE/ST=Berlin/L=Berlin/O=Spryker/CN=Spryker"
  openssl x509 -req -days 9999 -in ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.csr -signkey ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.key -out ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.crt

  if [ ! -f ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.pfx ]; then
    # Generate PFX file for import on client side
    ${ifVerbose} && echo -e "${INFO}Generating PFX file for CA to import on client side${NC}"
    openssl pkcs12 \
      -export \
      -out ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.pfx \
      -inkey ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.key \
      -in ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.crt \
      -password pass:${PFX_PASSWORD}
  fi
else
  ${ifVerbose} && echo -e "${INFO}CA cert already exists. Skipping new cert generation.${NC}"
fi

if [ ! -f ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.key -a ! -f ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.crt ]; then
  # Setting subjectAltName field for server certificate
  if [ -n "${SPRYKER_FE_HOST}" -a -n "${SPRYKER_BE_HOST}" -a -n "${SPRYKER_API_HOST}" ]; then
    export ALT_NAMES="DNS:${SPRYKER_FE_HOST},DNS:${SPRYKER_BE_HOST},DNS:${SPRYKER_API_HOST}"
    ${ifVerbose} && echo -e "${INFO}Generating cert for ${APPLICATION_STORE} hosts${NC}"
    for host in $(echo ${SPRYKER_FE_HOST} ${SPRYKER_BE_HOST} ${SPRYKER_API_HOST}); do
      ${ifVerbose} && echo -e "${CYAN}  - ${host}${NC}"
    done
  else
    export ALT_NAMES="DNS:${SPRYKER_SCHEDULER_HOST},DNS:${SPRYKER_BROKER_HOST},DNS:${SPRYKER_MAIL_HOST}"
    ${ifVerbose} && echo -e "${INFO}Generating cert for additional services${NC}"
    for host in $(echo ${SPRYKER_SCHEDULER_HOST} ${SPRYKER_BROKER_HOST} ${SPRYKER_MAIL_HOST}); do
      ${ifVerbose} && echo -e "${CYAN}  - ${host}${NC}"
    done
  fi

  openssl req \
    -nodes \
    -newkey rsa:2048 \
    -out ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.csr \
    -keyout ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.key \
    -new \
    -sha256 \
    -extensions v3_req \
    -config <( cat /opt/openssl/v3.ext )
  
  openssl x509 \
    -CA ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.crt \
    -CAkey ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.key \
    -CAcreateserial \
    -req \
    -days 365 \
    -in ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.csr \
    -out ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.crt \
    -extfile /opt/openssl/v3.ext \
    -extensions v3_req

  cat ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.crt >> ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.crt
else
  ${ifVerbose} && echo -e "${INFO}Cert ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.crt already exists. Skipping new cert generation.${NC}"
fi

# Check certificate chain
${ifVerbose} && echo -e "${INFO}Checking chain${NC}"
openssl verify \
  -verbose \
  -CAfile ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ca.crt \
  ${CERTS_DIR}/${SPRYKER_DOCKER_PREFIX}_ssl_${APPLICATION_STORE}.crt
