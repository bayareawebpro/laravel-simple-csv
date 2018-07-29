#!/usr/bin/env bash
_PATH=$1
_CHUNKS=$2
_INDEX=1
_HEADER=$(head -1 "${_PATH}")
_NAME=$(basename "${_PATH}" .csv)
_DIR=$(dirname -- "${_PATH}")
split -l "${_CHUNKS}" "${_PATH}" _LINES

for _LINE in _LINES*
do
	if (( _INDEX > 1 )); then
        echo ${_HEADER} > ${_DIR}/${_NAME}-chunk-${_INDEX}.csv
	fi
	cat ${_LINE} >> ${_DIR}/${_NAME}-chunk-${_INDEX}.csv
	rm ${_LINE}
	((_INDEX++))
done