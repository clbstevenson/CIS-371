import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;

public class BasicWebServer_Simple {

    
    public static void main(String[] args) {
        // For securit reasons, only root can use ports < 1024     
        final int DEFAULT_PORT = 8080; 
        // use DEFAULT_PORT unless a port is specified in cmd args
        int portNumber = args.length > 1 ? Integer.parseInt(args[0]) : DEFAULT_PORT;
        
        try {
            ServerSocket serverSocket = new ServerSocket(portNumber);
            Socket clientSocket = serverSocket.accept();
            // note: might change to PrintStream to handle data and text
            PrintWriter out = new PrintWriter(
                clientSocket.getOutputStream(), true);
            BufferedReader in = new BufferedReader(
                new InputStreamReader(clientSocket.getInputStream()));
        } catch(IOException e) {
            System.err.println("Oops!  " + e);
        }

        //TODO: read data from input until blank line
        
    }
}_
