import Plugin from 'src/plugin-system/plugin.class';

export default class CgsPlugin extends Plugin
{
    static options={
        test:'You have reached the page end',
    };
    init()
    {
        window.addEventListener('scroll',this.onScroll.bind(this));
    }
    onScroll()
    {
        if((window.innerHeight+window.pageYOffset)>=document.body.offsetHeight){
            alert(this.options.test);
            console.log('alert is triggered');
        }

    }
}