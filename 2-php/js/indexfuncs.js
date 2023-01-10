b=document.getElementsByTagName('body')[0]
datas=[];
for (i=0; i < b.children.length; i++){ 
    datas.push({'pid':i, 'chid':[]});
    if (b.children[i].style.paddingLeft.slice(0,-2)<b.children[i+1].style.paddingLeft.slice(0,-2)){
        datas[i]['chid'].push({'pid':i+1})
        b.children[i].children[0].children[0].setAttribute('row',i)
        k=1;
        stopfor=false;
        childPad="";
        for (j =i+1; j< b.children.length; j++) {
            if (stopfor==false){
                if (b.children[i].style.paddingLeft.slice(0,-2)< b.children[j].style.paddingLeft.slice(0,-2))
                {
                    if (childPad==''){
                        childPad=b.children[j].style.paddingLeft.slice(0,-2);
                        datas[i]['chid'].push({'pid':j,})
                        b.children[j].style.display='none';
                    }else if(childPad==b.children[j].style.paddingLeft.slice(0,-2)){
                        datas[i]['chid'].push({'pid':j,})
                        b.children[j].style.display='none';
                    }
                }else if (childPad> b.children[j].style.paddingLeft.slice(0,-2)){
                    stopfor=true;
                }
            }
        }
            while (b.children[i+1].style.paddingLeft.slice(0,-2)==b.children[i+1+k].style.paddingLeft.slice(0,-2) || b.children[i].style.paddingLeft.slice(0,-2)+1 == b.children[i+1+k].style.paddingLeft.slice(0,-2)/30){
                datas[i]['chid'].push({'pid':i+1+k, 'chid':{}})
                k++;
            }

    }else{
        b.children[i].children[0].style.visibility='hidden';
    }
}
deleteClone();
function toggleChilds(id, action=null){
    childs=[];
    if (action==null){
        actDisp='';
    }
    for (let i = 0; i < datas[id]['chid'].length; i++) {
        goNext=false;
        for (let k = 0; k < childs.length; k++) {
            if (childs[k]==datas[id]['chid'][i]['pid']) {
                goNext=true;
                break;
            }
        }
        for (let x = 0; x < childs.length; x++) {
            if (childs[x]==datas[id]['chid'][i]['pid']){
                goNext=true;
            }
        }
        childs.push(datas[id]['chid'][i]['pid']);
        if (goNext==false) {
             if (b.children[datas[id]['chid'][i]['pid']].style.display=='none' && actDisp==''){
                 actDisp='flex';
             }else if (b.children[datas[id]['chid'][i]['pid']].style.display!='none' && actDisp==''){
                 actDisp='none';
              }
            if (b.children[datas[id]['chid'][i]['pid']].style.display=='none'){
                b.children[datas[id]['chid'][i]['pid']].style.display=actDisp;
            }else {
                b.children[datas[id]['chid'][i]['pid']].style.display=actDisp;
                
                for (let j = 0; j < datas[datas[id]['chid'][i]['pid']]['chid'].length; j++) {
                    toggleChilds(datas[datas[id]['chid'][i]['pid']]['pid'],actDisp);
                }
           }
        }
    }
}


function deleteClone(){
    for (let i = 0; i < datas.length; i++) {
        if (datas[i]['chid'].length>0) {
            tempArr=[];
            for (let k = 0; k < datas[i]['chid'].length; k++) {
                gobreak=false;
                if (tempArr.length>0){
                    for (let j = 0; j < tempArr.length; j++) {
                        if (tempArr[j]==datas[i]['chid'][k]['pid']){
                            datas[i]['chid'].splice(k,1);
                            gobreak=true;
                            break;
                        }
                    }
                }else{
                    tempArr.push(datas[i]['chid'][k]['pid']);
                }
                if (gobreak==false && tempArr.length>0){
                    tempArr.push(datas[i]['chid'][k]['pid']);
                }
            }

        }
    }
}