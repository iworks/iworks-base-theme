#!/bin/bash
BASE_NAME=iworks-base-theme

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
  TARGET="$(readlink "$SOURCE")"
  if [[ $SOURCE == /* ]]; then
    SOURCE="$TARGET"
  else
    DIR="$( dirname "$SOURCE" )"
    SOURCE="$DIR/$TARGET"
  fi
done
RDIR="$( dirname "$SOURCE" )"
DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"

THEME_DIR=$(dirname ${DIR})
THEME_POT_FILE=${THEME_DIR}/languages/${BASE_NAME}.pot
php -e ${HOME}/wordpress-i18n/makepot.php wp-theme ${THEME_DIR} ${THEME_POT_FILE}
TMP=`tempfile`
sed -e 's/FULL NAME <EMAIL@ADDRESS>/Marcin Pietrzak <marcin@iworks.pl>/' ${THEME_POT_FILE} > ${TMP}
cp ${TMP} ${THEME_POT_FILE}
sed -e 's/FIRST AUTHOR <EMAIL@ADDRESS>/Marcin Pietrzak <marcin@iworks.pl>/' ${THEME_POT_FILE} > ${TMP}
cp ${TMP} ${THEME_POT_FILE}
sed -e 's/LANGUAGE <LL@li.org>/Marcin Pietrzak <marcin@iworks.pl>/' ${THEME_POT_FILE} > ${TMP}
cp ${TMP} ${THEME_POT_FILE}
rm ${TMP}

cd ${THEME_DIR}/languages

FILE=pl_PL.po

if [ ! -f ${FILE} ]; then
    cp ${BASE_NAME}.pot ${FILE} && perl -pi -e 's/Language: /Language: pl_PL/' ${FILE}
fi

for ELEMENT in $(ls -1 *.po|sed -e s/\.po//)
do
    msgmerge -U ${ELEMENT}.po ${BASE_NAME}.pot
    msgfmt --statistics -v ${ELEMENT}.po -o ${ELEMENT}.mo
done

