echo -e "进入项目目录...\n"
cd {{ project_path }}

echo -e "fetch origin...\n"
git fetch origin

echo -e "获取当前分支...\n"
now_br=`git rev-parse --abbrev-ref HEAD`
tmp_br="tmp_____"

function ce() {
   if [[ $? -ne 0 ]]; then
       echo -e "${1}...\n"
        exit 5
   fi
}

if [[ "${now_br}" == "{{ branch }}" ]];then
    echo -e "生成临时分支...\n"
    git checkout -b ${tmp_br}
    ce "生成临时分支"

    echo -e "删除旧分支...\n"
    git branch -D ${now_br}
    ce  "删除旧分支"

    echo -e "co 新分支...\n"
    git chekcout "{{ branch }}"
    ce "co 新分支"
else
    echo -e "co 新分支...\n"
    git checkout "{{ branch }}"
     ce "co 新分支"
fi

echo -e "{{ server_name }} 上线完成....\n"



