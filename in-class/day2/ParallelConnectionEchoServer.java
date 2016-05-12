import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;

/**
 * Created by kurmasz on 5/4/15.
 * A simple echo server to demonstrate how to set up a server.
 * This server handles multiple connections in parallel.  Notice how the main method launches
 * a new thread each time a request comes in.
 */
public class ParallelConnectionEchoServer {


  public static void handleConnection(Socket socket, int clientNum) throws IOException {


    // Created a BufferedReader that can read from the socket
    BufferedReader input = new BufferedReader(new InputStreamReader(socket.getInputStream()));

    // Create a PrintStream than can write to the socket
    // Passing "true" as the second parameter causes each write to be followed by a flush.
    PrintStream output = new PrintStream(socket.getOutputStream(), true);

    // While there is input, read it in, and simply echo it back
    String line;
    line = input.readLine();
    while (line != null) {
      if (line.toLowerCase().trim().equals("bye")) {
        output.println("Good bye.\n");
        socket.close();
        break;
      }

      output.println("You said: " + line);
      System.out.printf("Client %d said: \"%s\"\n", clientNum, line);
      line = input.readLine();
    }
  }


  public static void main(String[] args) throws IOException {

    // Create a socket that listens on port 8354.
    ServerSocket serverSocket = new ServerSocket(8354);

    // Use a separate thread to handle each connection so multiple connections can
    // be handled concurrently.
    for (int clientNum = 0; true; clientNum++) {
      final Socket socket = serverSocket.accept();
      final int localClientNum = clientNum;
      new Thread(new Runnable() {
        public void run() {
          try {
            handleConnection(socket, localClientNum);
          } catch (IOException e) {
            e.printStackTrace();
          }
        }
      }).start();

    }
  }
}
