/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package cuarto;


/**
 *
 * @author ASUS
 */
import java.sql.*;
import javax.swing.JOptionPane;
    
public class Conexion {
    
    Connection conect;
    
    public Connection conectar(){
        try {
            
            
            
            Class.forName ("com.mysql.cj.jdbc.Driver");
            
          conect=DriverManager.getConnection("jdbc:mysql://localhost:3306/cuarto", "root", "");
            
         //  JOptionPane.showMessageDialog(null, "Estas conectado");
         
            
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(null,"Se ha producido el error: "+ ex);
        }
          return conect;
    
    
    }

}
