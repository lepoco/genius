using System;
using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Hosting;
using Microsoft.Extensions.Logging;
using NLog;
using NLog.Web;

namespace Genius.Statistics;

public class Program
{
    public static void Main(string[] args)
    {
        var logger = LogManager.Setup().LoadConfigurationFromAppSettings().GetCurrentClassLogger();

        try
        {
            CreateHostBuilder(args).Build().Run();
        }
        catch (Exception exception)
        {
            //NLog: catch setup errors
            logger.Error(exception, "Stopped program because of exception");
            throw;
        }
        finally
        {
            LogManager.Shutdown();
        }
    }

    // Additional configuration is required to successfully run gRPC on macOS.
    // For instructions on how to configure Kestrel and gRPC clients on macOS, visit https://go.microsoft.com/fwlink/?linkid=2099682
    public static IHostBuilder CreateHostBuilder(string[] args) =>
        Host.CreateDefaultBuilder(args).ConfigureLogging(logging =>
            {
                logging.ClearProviders();
                logging.SetMinimumLevel(Microsoft.Extensions.Logging.LogLevel.Trace);
            })
            .ConfigureWebHostDefaults(webBuilder => { webBuilder.UseStartup<Startup>(); })
            .UseNLog();
}

