import java.io.*;
//import java.net.*;
//import java.sql.*;
import java.util.*;
//import com.hp.hpl.jena.query.*;
import com.hp.hpl.jena.util.*;
import com.hp.hpl.jena.rdf.model.*;
//import com.hp.hpl.jena.vocabulary.*;

//import de.fuberlin.wiwiss.ng4j.*;
//import de.fuberlin.wiwiss.ng4j.db.*;
//import de.fuberlin.wiwiss.ng4j.sparql.*;

public class RDFSReasonerRemoteImporter{

public static void main (String args[]) throws Exception{
		FileOutputStream RDFFile = new FileOutputStream(new File(args[1]));
		System.out.print(SQSTimestamp.getTimestamp("time")+" Processing "+args[0]);
		Model schema = FileManager.get().loadModel(args[0]);
		System.out.println(" Processed");
		System.out.print(SQSTimestamp.getTimestamp("time")+" Creating InfModel ");
		InfModel infmodel = ModelFactory.createRDFSModel(schema);
		System.out.println("Done");
		System.out.print(SQSTimestamp.getTimestamp("time")+" Printing InfModel to "+args[1]);
       		infmodel.write(RDFFile,"RDF/XML",null);
		System.out.println("Done");
}
}
