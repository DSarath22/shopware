import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';

export default class CalculatorPlugin extends Plugin
{
    init(){       
        // this.calculateVal();
        addEventListener('click',this.onClick.bind(this));
    }
    onClick(){
        this.one=this.el.children['one'];
        this.two=this.el.children['two'];
        this.three=this.el.children['three'];
    }
        
    
   
    
}