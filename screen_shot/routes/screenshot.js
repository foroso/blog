const express = require('express');
const router = express.Router();
const puppeteer = require('puppeteer');
const devices = require('puppeteer/DeviceDescriptors');
const fs = require('fs'); // 引入fs模块
const OSS = require('ali-oss') //阿里云oss模块

const getBrowser = () => puppeteer.launch({headless:true, lang:'zh-CN', args: ['--no-sandbox', '--disable-setuid-sandbox']});

//获取上传进度
const progress = async function progress(p, checkpoint) { console.log(p) };

const iPhone = devices['iPhone 7'];

let client = new OSS({
    region: 'oss-cn-hangzhou',
    //云账号AccessKey有所有API访问权限，建议遵循阿里云安全最佳实践，部署在服务端使用RAM子账号或STS，部署在客户端使用STS。
    accessKeyId: 'zNtN2fZLuzqSgknp',
    accessKeySecret: 'PcDotyZL0whmBTNwY76qhyfkimTAJ1',
    bucket: 'shangjiadao'
  });

  //生成唯一的文件名
  function randname(){
    return new Date().getTime()+Math.random().toString(36).substr(2)+".jpg";
  }


  //获取图片路径,默认为1
  function getpath(type = 1){
    
    let path='';

    switch(type){
    case 1:
      path = 'shop/download/';
      break;
    case 2:
     path = 'qrcode/download/';
      break;
    default:
      path = 'flyer/download/';
    }

    return path;
  }

/* GET 服务 */
/*
router.get('/', function(req, res, next) {
    res.render('get', { title: 'this is get method' });
});
*/


//POST 服务,返回json格式数据
router.post('/', function (req, res) {
    var url = req.body.url;
    var filename = req.body.filename;
    var img_width = +req.body.width;
    var img_height = +req.body.height;

    var object = req.body.object || '';

    console.log(new Date());
    console.log(req.body);

    // var filename = '/data/public/' + (new Date()).getTime() + '.jpg';

    filename = '/data/public/' + randname();


    getBrowser().then(async browser => {
        const page = await browser.newPage();
        
        //设置模拟设备
        await page.emulate(iPhone);

        //设置页面大小
        await page.setViewport({
            width: img_width,
            height: img_height
        });

        //打开页面
        await page.goto(url, {
            timeout:10000,
            waitUntil: 'networkidle2'
        });

        //等待加载，单位：ms（毫秒）1000ms = 1s
        // await timeout(300);

        //截图
        await page.screenshot({path: filename, fullPage: true});
        
        //关闭浏览器
        await browser.close();

        //图片上传到oss
       // let object = 'flyer/download/' + randname();
       if(object==''){
          object = 'flyer/download/' + randname();
       }
    

        try {
        
         let result = await client.multipartUpload(object, filename, {mime: 'image/jpeg'});

         //返回信息
         let head = await client.head(object);

         //上传成功后删除文件
         if(result.res.status == 200){
            await fs.unlinkSync(filename);
         }

         console.log(result);
         
        //返回信息
        res.json({'image_name': result.name});

        } catch (e) {
          // 捕获超时异常
          if (e.code === 'ConnectionTimeoutError') {
            console.log("上传超时!");
            res.json({'result': '上传超时!'});
          }
          res.json(e);
        }
        
        
    });

})

module.exports = router;
