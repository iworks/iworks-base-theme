#!/bin/bash
echo "Enter new theme name:"
read THEME_NAME

THEME_VERSION=`date +%y.%m`
THEME_CODE=${THEME_NAME// /_}
THEME_CODE=${THEME_CODE//./_}
BASE_NAME=${THEME_VERSION/./}-${THEME_CODE,,}
THEME_DIR=./${BASE_NAME}
THEME_POT_FILE=${THEME_DIR}/languages/${THEME_CODE,,}.pot
THEME_CODE=${THEME_CODE^}

if [ -d ${THEME_DIR} ]; then
    echo "Theme dir (${THEME_DIR}) already exists!"
    exit 0
fi

echo "Generating theme:"
echo "  name:    ${THEME_NAME}"
echo "  code:    ${THEME_CODE}"
echo "  version: ${THEME_VERSION}"
echo "  dir:     ${THEME_DIR}"

git clone git@github.com:iworks/iworks-base-theme.git ${THEME_DIR}
cd ${THEME_DIR}
git checkout "3.0.0"
cd ..
rm -rf ${THEME_DIR}/.git

( cat ${THEME_DIR}/includes/generic_iworks_theme.php|sed "s/%DATE%/$(date '+%B %Y')/" ) > ${THEME_DIR}/includes/${THEME_CODE}.php
rm ${THEME_DIR}/includes/generic_iworks_theme.php

# style.dev.css
perl -pi -e "s/Generic_iWorks_Theme_URL/${THEME_CODE,,}_${THEME_VERSION/./}_wordpress_theme/g" ${THEME_DIR}/style.dev.css
perl -pi -e "s/Generic iWorks Theme/${THEME_NAME}/g" ${THEME_DIR}/style.dev.css
perl -pi -e "s/XX.XX/${THEME_VERSION}/g" ${THEME_DIR}/style.dev.css
perl -pi -e "s/Generic_iWorks_Theme/${THEME_CODE}/g" ${THEME_DIR}/style.dev.css
cp ${THEME_DIR}/style.dev.css ${THEME_DIR}/style.css

# *.php
perl -pi -e "s/Generic iWorks Theme/${THEME_NAME}/g" ${THEME_DIR}/*.php
perl -pi -e "s/Generic_iWorks_Theme/${THEME_CODE}/g" ${THEME_DIR}/*.php
perl -pi -e "s/Generic iWorks Theme/${THEME_NAME}/g" ${THEME_DIR}/includes/*.php
perl -pi -e "s/Generic_iWorks_Theme/${THEME_CODE}/g" ${THEME_DIR}/includes/*.php

# pot file
mv ${THEME_DIR}/languages/iworks-base-theme.pot ${THEME_POT_FILE}
perl -pi -e "s/Generic_iWorks_Theme/${THEME_CODE}/g" ${THEME_POT_FILE}

# tools
perl -pi -e "s/(Generic_iWorks_Theme|iworks-base-theme)/${THEME_CODE,,}/g" ${THEME_DIR}/tools/minifizer.pl
perl -pi -e "s/(Generic_iWorks_Theme|iworks-base-theme)/${THEME_CODE,,}/g" ${THEME_DIR}/tools/make-i18n.sh

