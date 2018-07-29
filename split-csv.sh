#!/usr/bin/env bash
_PATH=$1
_CHUNKS=$2
_INDEX=0
_HEADER=$(head -1 "${_PATH}")
_NAME=$(basename "${_PATH}" .csv)
_DIR=$(dirname -- "${_PATH}")
split -l "${_CHUNKS}" "${_PATH}" _LINES

for _LINE in _LINES*
do
	if (( _INDEX > 0 )); then
        echo ${_HEADER} > ${_DIR}/${_NAME}-${_INDEX}.csv
	fi
	cat ${_LINE} >> ${_DIR}/${_NAME}-${_INDEX}.csv
	rm ${_LINE}
	((_INDEX++))
done