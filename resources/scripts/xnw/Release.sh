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

function del_old_br() {
   br_list=(`git branch`)
   for br in "${br_list[@]}";
   do
        if [[ "${br}" == "{{ branch }}" ]]; then
            echo -e "删除旧分支...\n"
            git branch -D "${br}"
            ce  "删除旧分支"
        fi
   done
}

if [[ "${now_br}" == "{{ branch }}" ]];then
    echo -e "生成临时分支...\n"
    git checkout -b ${tmp_br}
    ce "生成临时分支"

    del_old_br

    echo -e "co 新分支...\n"
    git checkout "{{ branch }}"
    ce "co 新分支"
else
    del_old_br

    echo -e "co 新分支...\n"
    git checkout "{{ branch }}"
     ce "co 新分支"
fi

#
# grunt release && sassc && artc
# cd laravel
# composer install -o --no-dev
# yarn
# npm run production
# php artisan up
#
echo -e "{{ server_name }} 上线完成....\n"
