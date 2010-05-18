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

public class RDFSReasonerOntologyMerger{
public static void addStatements(Model om, Model m, Resource s, Property p, Resource o) {
        for (StmtIterator i = m.listStatements(s,p,o); i.hasNext(); ) {
                Statement stmt = i.nextStatement();
//                if (stmt.getObject().isAnon() == false){
                        om.add(stmt);
//                }
        }

}
public static void main (String args[]) {
	String[] urls = new String[100];
	int r=0;
	BufferedReader br;
	try{
                Vector<String> vec = new Vector<String>(100);
                br = new BufferedReader(new FileReader(args[0]));
                while (br.ready()){
                        vec.add(br.readLine());
                }
                urls = (String[])vec.toArray(new String[vec.size()]);

        }
        catch (IOException ioe){
                System.err.println("false");
                ioe.printStackTrace();
                System.exit(1);
        }
	System.err.print(SQSTimestamp.getTimestamp("time")+" Processing "+urls[0]);
	Model schema = FileManager.get().loadModel(urls[0]);
	System.err.println(" Processed");
	for (int i=1; i<urls.length; i++){
	        System.err.print(SQSTimestamp.getTimestamp("time")+" Processing "+urls[i]);
		schema = FileManager.get().readModel(schema,urls[i]);
		System.err.println(" Processed");
	}
	System.err.print(SQSTimestamp.getTimestamp("time")+" Creating InfModel ");
	InfModel infmodel = ModelFactory.createRDFSModel(schema);
	System.err.println("Done");
	System.err.println(SQSTimestamp.getTimestamp("time")+" Model has "+infmodel.size()+" triples");
        System.err.print(SQSTimestamp.getTimestamp("time")+" Rationalising Model");
        Model outmodel = ModelFactory.createDefaultModel();
        for ( ResIterator ri = infmodel.listSubjects(); ri.hasNext(); ) {
                Resource res = ri.nextResource();
//		if (res.isAnon() == false){
      	        	addStatements(outmodel,infmodel,res,null,null);
//		}
               	if (r % 100 == 0){
                       	System.err.print(".");
                }
                r++;
        }
	System.err.print(SQSTimestamp.getTimestamp("time")+" Printing InfModel to System.out ");
	System.out.println("<?xml version=\"1.0\" encoding=\"UTF-8\"?>");
       	outmodel.write(System.out,"RDF/XML",null);
	System.err.println("Done");
}
}
