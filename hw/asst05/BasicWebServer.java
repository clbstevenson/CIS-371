import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;

/**
 * Created by stevecal on 5/12/16
 * Basic structural code borrowed from kurmasz, 
 *      https://github.com/kurmasz/CS371_SampleCode/blob/master/Server
 * This server handles multiple connections by launching new threads
 * for each connection.
 */
public class BasicWebServer {
    
    public static void handleConnection(Socket socket, int clientNum) throws IOException {
    
        System.out.printf("Client %d connected.\n", clientNum);

        // Create a BufferedReader to read in from the socket
        BufferedReader in = new BufferedReader(
            new InputStreamReader(socket.getInputStream()));
        // Create a PrintWriter to write output to the socket
        // note: may need to change to PrintStream instead of PrintWriter
        PrintStream out = new PrintStream(
            socket.getOutputStream(), true);
        //PrintWriter out = new PrintWriter(
        //    socket.getOutputStream(), true);

        // while there is input, read until a blank line 
        String clientRequest; 
        // Continue to read data from the client until
        // there is an empty line
        String[] headerLines = new String[256];
        int linenum = 0;
        while (!((clientRequest = in.readLine()).isEmpty())) {
            System.out.println(clientRequest);
            headerLines[linenum] = clientRequest;
            linenum++;
        }
        System.out.println("Completed reading header.");

        //: send response back to client with a blank line at the end
        /* The following String and println was the simple demo
         * for in-class assignment 3. 
         * It has been replaced by parsing the header and 
         * sending other content-type, length, etc below.
        String serverResponse = "HTTP/1.1 200 OK\n" +
            "Content-Type: text/plain\n" + "Content-Length: 70\n" + 
            "Connection: close\n"; // include a blank line
            //"WIP: This is not the real content. Work in Progress";
        out.println(serverResponse);
        */
        /*NOTE: chrome requires the content-length to match the content
          if it doesn't, then it will display an error. 
          Firefox does not seem to care if content-length matches
        */

        // 2: parse the header lines
        System.out.println("Parsing header data...");
        // parse the GET line from the client's header request
        System.out.printf("\t%s\n", headerLines[0]);
        String[] getSplit = headerLines[0].split(" ");

        // 3: open the requested file
        // use a FileInputStream instead of FileReader for instances
        // where the file contains non-character data (ie images)
        File file = new File("." + getSplit[1]);
        //File file = new File("test1.html");
        if (!file.exists()) {
            echoPrint(out, "HTTP/1.1 404 NOT FOUND");
            out.println("");
            closeConnection(socket, clientNum);
            return;
        }

        echoPrint(out, "HTTP/1.1 200 OK");
        //out.println("HTTP/1.1 200 OK");
        //System.out.println("R: HTTP/1.1 200 OK"); 

        //TODO: read the rest of the data
        InputStream fileIn = null;
        try {
            // 4: print the required response headers (content-type & -length)
            //    You may base the content-type on the file extension

            // print the content-length of the requested file
            echoPrint(out,"Content-Length: " + file.length()); 
            //out.printf("Content-Length: %d\n", file.length());
            //System.out.printf("R: Content-Length: %d\n", file.length());

            // print the content-type of the requested file 
            // > finds the index of '.', and then returns the 
            //   substring of the fileName following the '.'
            String fileName = getSplit[1];
            System.out.printf("\t%s\n", fileName);
            System.out.printf("\t%d\n", fileName.indexOf('.'));
            String fileType = fileName.substring(fileName.indexOf('.') + 1);
            echoPrint(out, "Content-Type: " + fileType);

            // begin reading data from the requested file
            // create a FileInputStream so binary data can be read
            fileIn = new FileInputStream(file);
            byte[] buffer = new byte[1024];
            // read up to 1024 bytes of raw data
            int amount_read = fileIn.read(buffer);
            // write data back out to an OutputStream
            out.write(buffer, 0, amount_read);
        } catch(FileNotFoundException fnfe) {
            System.err.println("No file:  " + fnfe);
        }

        /*
        out.println("HTTP/1.1 200 OK");
        out.println("Content-Type: text/plain");
        out.println("Content-Length: 9");
        out.println("Connection: close");
        out.println("");
        out.println("123456789");
        */
        out.flush();

        // 
        closeConnection(socket, clientNum);


    }

    public static void closeConnection(Socket socket, int clientNum) throws IOException{
        System.out.printf("Closing connection: client %d\n", clientNum);
        socket.close();
    }

    // echos @text to both the OutputStream and stdout
    public static void echoPrint(PrintStream out, String text) {
        out.println(text);
        System.out.printf("R: " + text + "\n");
    }


    public static void main(String[] args) throws IOException {
        final int DEFAULT_PORT = 8080;
        // if specified, use cmd args as port number; otherwise port 8080
        int portNumber = args.length > 1 ? Integer.parseInt(args[0]) : DEFAULT_PORT;
        
        ServerSocket serverSocket = new ServerSocket(portNumber);
        for(int clientNum = 0; true; clientNum++) {
            final Socket socket = serverSocket.accept();
            final int localClientNum = clientNum;
            // Create a new Thread for each client connection
            // to allow multiple clients to be connected concurrently
            new Thread(new Runnable() {
                public void run() {
                    try {
                        // handle the connection for client number @localClientNum
                        handleConnection(socket, localClientNum);
                    } catch(IOException e) {
                        System.err.println("Oops!  " + e);
                        System.out.println();
                        e.printStackTrace();
                    }
                }
            }).start();
        }

    }
}
