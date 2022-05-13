const express = require('express');
const multer = require('multer');
const path = require('path');
const connection = require('../config/db.connect');
const fs = require('fs');
let imgName;

const router = require("express").Router();

const UPLOADS_FOLDER = 'C://xampp/htdocs/CRUD/postsimages/';

// define the storage

const storage = multer.diskStorage({
    destination: (req, res, cb) => {
        cb(null, UPLOADS_FOLDER);
    },

    filename: (req, file, cb) => {
        const fileExt = path.extname(file.originalname);
        const filename = file.originalname
                            .replace(fileExt, "")
                            .toLowerCase()
                            .split(" ")
                            .join("-") + "-" + Date.now();
                            imgName = filename+fileExt;
        cb(null, filename+fileExt);
    }
});


// prepare the final multer upload object
var upload = multer({
    storage: storage,
    fileFilter: (req,file,cb)=>{
        if(file.fieldname === 'file') {
            if(
                file.mimetype === 'image/png' ||
                file.mimetype === 'image/jpg' ||
                file.mimetype === 'image/jpeg'
            ) {
                cb(null, true);
            } else {
                cb(new Error("Only .jpg .png or .jpeg format allowed!"));
            }
        }
         else {
            cb(new Error("There was an unknown Error!"));
        }
        
    }
});



// delete file from local storage
function unlink(dir, file, callback){
    fs.unlink(`${dir}/${file}`,(err) => {
        if(!err){
            callback(false);
        } else {
            callback(`Error deleting file`);
        }
    });
}

// GET Posts

router.get("/", async (req, res, next) => {
    try {
        const query = 'SELECT * FROM tbl_post';
        connection.query(query,(err,result)=>{
            if(!err) {
               res.json(result);
                
            } else {
                res.status(500).send("Server site error!");
            }
        })
    } catch (error) {
        console.error(`Error while getting categories data `, error.message);
        next(error);
    }
});

router.get("/:id",async (req,res)=>{
    const { id } = req.params;
    const sqlSelect = "SELECT * FROM tbl_post WHERE id = ?";
    connection.query(sqlSelect,id,(err,result)=>{
        if(!err) {
            res.send(result);
        } else {
            res.status(500).send("Server site error!");
        }
    });
});


router.get("/status/code", async (req, res, next) => {
    try {
        const query = "SELECT * FROM tbl_post where status ='pending'";
        connection.query(query,(err,result)=>{
            if(!err) {
               res.json(result);
                
            } else {
                res.status(500).send("Server site error!");
            }
        })
    } catch (error) {
        console.error(`Error while getting categories data `, error.message);
        next(error);
    }
});

router.post("/status/code/:id", async (req, res, next) => {
    try {
        const { id } = req.params;
        const query = "UPDATE tbl_post SET status = 'approved' WHERE id = ?";
        
        connection.query(query,id,(err,result)=>{
            if(!err) {
                res.send("update user successfully!");
            } else {
                res.status(500).send("Server site error!");
            }
        });
    } catch (error) {
        console.error(`Error while getting categories data `, error.message);
        next(error);
    }
});

router.post("/",upload.single('file'), (req,res)=>{
    const {title, desc, cat, catId, file} = req.body;
    const img = `postsimages/${imgName}`;
    const sqlInsert = `INSERT INTO tbl_post ( cat_id, title, description, image, author, tags) VALUES (?, ?, ?, ?, 'Admin' ,?)`;
    connection.query(sqlInsert,[catId, title, desc, img, cat], (err, result)=>{
        if(err) {
            res.status(500).send("Server site error!");
        } else {
            res.send("Data Inserted Successfully!");
        }
    });

})


router.post("/update/:id",upload.single('file'), (req,res)=>{
    const { id } = req.params;
    const {title, desc, cat, catId, prevFile, file} = req.body;
    const img = `postsimages/${imgName}`;
    const query = "UPDATE tbl_post SET cat_id = ?, title = ?, description = ?, image = ?, tags = ? WHERE id = ?";
    connection.query(query,[catId, title, desc, img, cat, id],(err,result)=>{
        if(!err) {
            fs.unlink(`C://xampp/htdocs/CRUD/${prevFile}`,(err)=>{
                if(!err){
                    res.send("Data Deleted Successfully!");
                } else {
                    res.status(500).send("Server site error!");
                }
            });
        } else {
            res.status(500).send("Server site error!");
        }
    });
});



router.delete("/:id",async (req,res)=>{
    const { id } = req.params;
    const { image } = req.body;
    const sqlRemove = "DELETE FROM tbl_post where id = ?";
    connection.query(sqlRemove,id, (err, result)=>{
        if(err) {
            res.status(500).send("Server site error!");
        } else {
            fs.unlink(`C://xampp/htdocs/CRUD/${image}`,(err)=>{
                if(!err){
                    res.send("Data Deleted Successfully!");
                } else {
                    res.status(500).send("Server site error!");
                }
            } )
            
        }
    });
});

module.exports = router;
