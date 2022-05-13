const express = require('express');
const  connection = require('../config/db.connect');
/*const config = require('../config/config');
const mysql = require('mysql');
*/
const router = require("express").Router();
/*
const connection =  mysql.createConnection(config.db);
connection.connect((err)=>{
    if(err) {
        console.log(err);
    } else {
        console.log("Connection Success!");
    }
});*/

/* GET Categories */
router.get("/", async (req, res, next) => {
    try {
        const query = 'SELECT id, name FROM tbl_cat';
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
    const sqlSelect = "SELECT * FROM tbl_cat WHERE id = ?";
    connection.query(sqlSelect,id,(err,result)=>{
        if(!err) {
            res.send(result);
        } else {
            res.status(500).send("Server site error!");
        }
    });
});

router.post("/", async (req, res, next) => {
    try {
        const { cat } = req.body;
        const sqlInsert = `INSERT INTO tbl_cat (name) VALUES (?)`;
        connection.query(sqlInsert,cat, (err, result)=>{
            if(err) {
                res.status(500).send("Server site error!");
            } else {
                res.send("Data Inserted Successfully!");
            }
        });
    } catch (error) {
        console.error(`Error while getting categories data `, error.message);
        next(error);
    }
});


router.put("/:id",async (req,res)=>{
    const { id } = req.params;
    const { cat } = req.body;
    console.log(cat)
    const sqlSelect = "UPDATE tbl_cat SET name = ? WHERE id = ?";
    connection.query(sqlSelect,[cat, id],(err,result)=>{
        if(!err) {
            res.send(result);
        } else {
            res.status(500).send("Server site error!");
        }
    });
});

router.delete("/:id",async (req,res)=>{
    const { id } = req.params;
    const sqlRemove = "DELETE FROM tbl_cat where id = ?";
    connection.query(sqlRemove,id, (err, result)=>{
        if(err) {
            res.status(500).send("Server site error!");
        } else {
            res.send("Data Deleted Successfully!");
        }
    });
});

module.exports = router;
